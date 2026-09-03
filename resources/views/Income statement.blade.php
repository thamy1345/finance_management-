@extends('layouts.admin')
@section('title','Income Statement')
@section('page-title','Income Statement')

@push('styles')
<style>@media print{.sidebar,.topbar,.topbar-right,.no-print{display:none!important}.main-wrap{margin-left:0}body{background:#fff}}</style>
@endpush

@section('content')
<div class="page-header no-print">
    <h1>Income Statement</h1>
    <div class="actions">
        <button onclick="window.print()" class="btn"><i class="ti ti-printer"></i> Print</button>
    </div>
</div>

<form method="GET" class="filter-bar no-print">
    <select name="year" class="form-control">
        @foreach(range(date('Y'),date('Y')-3) as $y)
            <option value="{{ $y }}" {{ $year==$y?'selected':''}}>{{ $y }}</option>
        @endforeach
    </select>
    <select name="term" class="form-control">
        <option value="">Full Year</option>
        @foreach(['Term 1','Term 2','Term 3'] as $t)
            <option value="{{ $t }}" {{ $term===$t?'selected':''}}>{{ $t }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-primary"><i class="ti ti-refresh"></i> Generate</button>
</form>

{{-- Statement header --}}
<div style="text-align:center;margin-bottom:20px">
    <h2 style="font-size:18px;font-weight:700">FinLedger School</h2>
    <p class="text-muted" style="font-size:13px">Income & Expenditure Statement</p>
    <p class="fw-600" style="font-size:14px">{{ $term ?: 'Full Year' }} — {{ $year }}</p>
</div>

<div class="grid-2" style="margin-bottom:16px">
    {{-- Income --}}
    <div class="card">
        <div class="card-header" style="background:#EAF3DE">
            <span class="card-title text-green">INCOME</span>
        </div>
        <div class="table-wrap">
            <table class="tbl">
                <thead><tr><th>Category</th><th class="text-right">Amount (KES)</th></tr></thead>
                <tbody>
                <tr>
                    <td class="fw-600">Fee Collections</td>
                    <td class="text-right fw-600 text-green">{{ number_format($feesCollected) }}</td>
                </tr>
                @foreach($incomeByCategory as $row)
                <tr>
                    <td>{{ $row->category }}</td>
                    <td class="text-right text-green">{{ number_format($row->total) }}</td>
                </tr>
                @endforeach
                <tr style="border-top:2px solid var(--border)">
                    <td class="fw-600" style="font-size:14px">TOTAL INCOME</td>
                    <td class="text-right fw-600 text-green" style="font-size:15px">{{ number_format($totalIncome) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Expenditure --}}
    <div class="card">
        <div class="card-header" style="background:#FCEBEB">
            <span class="card-title text-red">EXPENDITURE</span>
        </div>
        <div class="table-wrap">
            <table class="tbl">
                <thead><tr><th>Category</th><th class="text-right">Amount (KES)</th></tr></thead>
                <tbody>
                @forelse($expenseByCategory as $row)
                <tr>
                    <td>{{ $row->category }}</td>
                    <td class="text-right text-red">{{ number_format($row->total) }}</td>
                </tr>
                @empty
                <tr><td colspan="2" class="text-center text-muted" style="padding:20px">No expenses recorded</td></tr>
                @endforelse
                <tr style="border-top:2px solid var(--border)">
                    <td class="fw-600" style="font-size:14px">TOTAL EXPENDITURE</td>
                    <td class="text-right fw-600 text-red" style="font-size:15px">{{ number_format($totalExpenses) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Net balance --}}
<div class="card">
    <div class="card-body" style="padding:20px 28px">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px">
            <div style="display:flex;gap:32px;font-size:13px">
                <div><div class="text-muted" style="margin-bottom:2px">Total Income</div><div class="fw-600 text-green" style="font-size:16px">KES {{ number_format($totalIncome) }}</div></div>
                <div style="font-size:24px;color:var(--muted);line-height:1.6">−</div>
                <div><div class="text-muted" style="margin-bottom:2px">Total Expenditure</div><div class="fw-600 text-red" style="font-size:16px">KES {{ number_format($totalExpenses) }}</div></div>
                <div style="font-size:24px;color:var(--muted);line-height:1.6">=</div>
                <div>
                    <div class="text-muted" style="margin-bottom:2px">Net {{ $netBalance >= 0 ? 'Surplus' : 'Deficit' }}</div>
                    <div class="fw-600 {{ $netBalance >= 0 ? 'text-green' : 'text-red' }}" style="font-size:22px">KES {{ number_format(abs($netBalance)) }}</div>
                </div>
            </div>
            <span class="badge {{ $netBalance >= 0 ? 'badge-success' : 'badge-danger' }}" style="font-size:14px;padding:8px 18px">
                {{ $netBalance >= 0 ? 'SURPLUS' : 'DEFICIT' }}
            </span>
        </div>
    </div>
</div>

{{-- Bar chart --}}
<div class="card no-print" style="margin-top:16px">
    <div class="card-header"><span class="card-title">Visual Breakdown</span></div>
    <div class="card-body">
        <div style="height:260px;position:relative">
            <canvas id="statementChart" role="img" aria-label="Income versus expenditure chart">Income and expenditure chart</canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const incomeData  = [{{ $feesCollected }}, @foreach($incomeByCategory as $r){{ $r->total }},@endforeach];
const incomeLabels= ['Fee Collections', @foreach($incomeByCategory as $r)'{{ $r->category }}',@endforeach];
const expData     = [@foreach($expenseByCategory as $r){{ $r->total }},@endforeach];
const expLabels   = [@foreach($expenseByCategory as $r)'{{ $r->category }}',@endforeach];

new Chart(document.getElementById('statementChart'),{
    type:'bar',
    data:{
        labels:['Income','Expenditure'],
        datasets:[{
            label:'KES',
            data:[{{ $totalIncome }},{{ $totalExpenses }}],
            backgroundColor:['#5DCAA5','#F09595'],
            borderRadius:6,
            barThickness:60
        }]
    },
    options:{responsive:true,maintainAspectRatio:false,
        plugins:{legend:{display:false},tooltip:{callbacks:{label:ctx=>'KES '+ctx.raw.toLocaleString()}}},
        scales:{y:{ticks:{callback:v=>'KES '+(v/1000).toFixed(0)+'K'},grid:{color:'rgba(0,0,0,0.05)'}},x:{grid:{display:false}}}
    }
});
</script>
@endpush