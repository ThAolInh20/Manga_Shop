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


</style>

