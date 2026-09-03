import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Fix Livewire navigate error
Alpine.navigate = function(url) {
    window.location.href = url;
};

Alpine.start();