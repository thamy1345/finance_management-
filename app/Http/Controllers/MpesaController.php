<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use App\Models\Student;
use App\Models\MpesaTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MpesaController extends Controller
{
    private string $consumerKey    = 'vwulARvvkyXcxMULcIE0XBJwSDgpjMzKaOkQB4bV8ihW1q4U';
    private string $consumerSecret = '587hYnCcmWaiVSbuTkVi6NmjDHAk40z1ovTJfeebG6ZweWKnMjZ9Hr4o2CeePclJ';
    private string $shortcode      = '600982';
    private string $baseUrl        = 'https://sandbox.safaricom.co.ke';
    private string $callbackBase   = 'https://https://source-biodiversity-tribal-corporation.trycloudflare.com';

    // ═══════════════════════════════════════════════════
    // DEBUG
    // Route: GET /payment/debug
    // ═══════════════════════════════════════════════════
    public function debug()
    {
        $results = [];

        try {
            $token = $this->getAccessToken();
            $results['access_token'] = '✅ Token OK: ' . substr($token, 0, 20) . '...';
        } catch (\Exception $e) {
            $results['access_token'] = '❌ ' . $e->getMessage();
        }

        $results['curl_enabled']    = extension_loaded('curl')    ? '✅ yes' : '❌ no';
        $results['openssl_enabled'] = extension_loaded('openssl') ? '✅ yes' : '❌ no';
        $results['php_version']     = PHP_VERSION;
        $results['callback_base']   = $this->callbackBase;
        $results['shortcode']       = $this->shortcode;
        $results['confirmation_url'] = "{$this->callbackBase}/payment/confirm";
        $results['validation_url']   = "{$this->callbackBase}/payment/validate";

        return response()->json($results, 200, [], JSON_PRETTY_PRINT);
    }

    // ═══════════════════════════════════════════════════
    // GET ACCESS TOKEN
    // ═══════════════════════════════════════════════════
    private function getAccessToken(): string
    {
        $response = Http::timeout(30)
            ->withoutVerifying()
            ->withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get("{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials");

        Log::info('M-Pesa token response', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        if ($response->failed()) {
            throw new \Exception(
                'Token request failed. Status: ' . $response->status() .
                ' Body: ' . $response->body()
            );
        }

        $json = $response->json();

        if (empty($json['access_token'])) {
            throw new \Exception(
                'No access_token in response. Body: ' . $response->body()
            );
        }

        return $json['access_token'];
    }

    // ═══════════════════════════════════════════════════
    // REGISTER C2B URLS
    // Route: GET /payment/register
    // ═══════════════════════════════════════════════════
    public function registerUrls()
    {
        try {
            $token = $this->getAccessToken();
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to get access token',
                'message' => $e->getMessage(),
            ], 500);
        }

        $payload = [
            'ShortCode'       => $this->shortcode,
            'ResponseType'    => 'Completed',
            'ConfirmationURL' => trim($this->callbackBase) . '/payment/confirm',
'ValidationURL'   => trim($this->callbackBase) . '/payment/validate',
        ];

        Log::info('Registering C2B URLs', $payload);

        $response = Http::timeout(30)
            ->withoutVerifying()
            ->withToken($token)
            ->post("{$this->baseUrl}/mpesa/c2b/v1/registerurl", $payload);

        $responseData = $response->json() ?? ['raw' => $response->body()];

        Log::info('C2B Registration Response', $responseData);

        return response()->json([
            'message'          => 'Registration request sent',
            'response'         => $responseData,
            'confirmation_url' => "{$this->callbackBase}/payment/confirm",
            'validation_url'   => "{$this->callbackBase}/payment/validate",
        ]);
    }

    // ═══════════════════════════════════════════════════
    // VALIDATION
    // Route: POST /payment/validate
    // ═══════════════════════════════════════════════════
    public function validation(Request $request)
    {
        $data  = $request->all();
        $admNo = trim($data['BillRefNumber'] ?? '');

        Log::info('C2B Validation incoming', $data ?: ['empty' => true]);

        $student = Student::where('admission_number', $admNo)->first();

        if (!$student) {
            Log::warning('C2B validation rejected — unknown adm no', ['adm_no' => $admNo]);
            return response()->json([
                'ResultCode' => 'C2B00012',
                'ResultDesc' => 'Invalid account number',
            ]);
        }

        Log::info('C2B validation accepted', [
            'adm_no'  => $admNo,
            'student' => $student->name,
        ]);

        return response()->json([
            'ResultCode' => '0',
            'ResultDesc' => 'Accepted',
        ]);
    }

    // ═══════════════════════════════════════════════════
    // CONFIRMATION
    // Route: POST /payment/confirm
    // ═══════════════════════════════════════════════════
    public function confirmation(Request $request)
    {
        $data = $request->all();

        Log::info('C2B Confirmation incoming', $data ?: ['empty' => true]);

        $admNo     = trim($data['BillRefNumber'] ?? '');
        $amount    = floatval($data['TransAmount'] ?? 0);
        $mpesaCode = $data['TransID']             ?? '';
        $phone     = $data['MSISDN']              ?? '';
        $transTime = $data['TransTime']           ?? now()->format('YmdHis');
        $firstName = $data['FirstName']           ?? '';
        $lastName  = $data['LastName']            ?? '';
        $payerName = trim("{$firstName} {$lastName}") ?: $phone;

        // Find student by admission number
        $student = Student::where('admission_number', $admNo)->first();

        if (!$student) {
            Log::error('C2B confirmation — student not found', ['adm_no' => $admNo]);
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        // Prevent duplicate processing
        if (MpesaTransaction::where('mpesa_code', $mpesaCode)->exists()) {
            Log::warning('Duplicate transaction skipped', ['code' => $mpesaCode]);
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        $year        = date('Y');
        $term        = $this->getCurrentTerm();
        $paymentDate = Carbon::createFromFormat('YmdHis', $transTime)->toDateString();
        $receipt     = 'MPESA-' . strtoupper($mpesaCode);

        // Save raw transaction log
        MpesaTransaction::create([
            'mpesa_code'       => $mpesaCode,
            'student_id'       => $student->id,
            'admission_number' => $admNo,
            'phone'            => $phone,
            'payer_name'       => $payerName,
            'amount'           => $amount,
            'trans_time'       => $transTime,
            'raw_payload'      => json_encode($data),
        ]);

        // Create fee payment record
        FeePayment::create([
            'student_id'     => $student->id,
            'amount_paid'    => $amount,
            'payment_date'   => $paymentDate,
            'payment_method' => 'mpesa',
            'receipt_number' => $receipt,
            'academic_year'  => $year,
            'term'           => $term,
            'mpesa_code'     => $mpesaCode,
            'phone_number'   => $phone,
            'notes'          => "Auto-recorded via M-Pesa C2B. Payer: {$payerName}",
        ]);

        Log::info('✅ Fee payment auto-recorded', [
            'student'  => $student->name,
            'adm_no'   => $admNo,
            'amount'   => $amount,
            'mpesa_id' => $mpesaCode,
        ]);

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    // ═══════════════════════════════════════════════════
    // HELPER — current term by month
    // ═══════════════════════════════════════════════════
    private function getCurrentTerm(): string
    {
        $month = (int) date('n');
        if ($month <= 3) return 'Term 1';
        if ($month <= 7) return 'Term 2';
        return 'Term 3';
    }
}