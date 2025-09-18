@php
    $config = \App\Models\WebsiteCustom::first();
@endphp

<style>
:root {

    --secondary-color: {{ $config->secondary_color ?? '#333333' }};
    --font-family: {{ $config->font_family ?? 'Arial, sans-serif' }};
    --background-color: {{ $config->background_color ?? '#ffffff' }};
}

body {
    font-family: var(--font-family);
    background-color: var(--background-color);
    color: var(--secondary-color);
}

a {
    
}

a:hover {
    opacity: 0.8;
}

.btn-primary {
  

}

/* Nếu có background image */
@if($config->background)
body {
    background-image: url('{{ $config->background }}');
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
}
@endif
</style>
