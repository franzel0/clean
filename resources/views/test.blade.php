<head>
    <title>Test Page</title>
    
    @fluxAppearance
</head>

<body>
    <p>This is a test page to verify the layout and components.</p>
    <x-simple-autocomplete options="[1,2]">

    </x-simple-autocomplete>
    <h3>Flux</h3>
    <flux:button>Default</flux:button>
    <flux:button variant="primary">Primary</flux:button>
    <flux:button variant="filled">Filled</flux:button>
    <flux:button variant="danger">Danger</flux:button>
    <flux:button variant="ghost">Ghost</flux:button>
    <flux:button variant="subtle">Subtle</flux:button>
    @fluxScripts
</body>
