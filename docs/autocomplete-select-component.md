# Autocomplete Select Komponente

Eine wiederverwendbare Autocomplete/Typeahead-Select-Komponente für Laravel Livewire.

## Verwendung

### Basis Verwendung
```php
<x-autocomplete-select 
    :options="$users->toArray()"
    name="user_id"
    placeholder="Benutzer auswählen..."
/>
```

### Erweiterte Verwendung
```php
<x-autocomplete-select 
    :options="$instruments->toArray()"
    name="instrument_id"
    :value="$selectedInstrumentId"
    placeholder="Instrument auswählen..."
    :required="true"
    display-field="name"
    value-field="id"
    :search-fields="['name', 'serial_number']"
    secondary-display-field="serial_number"
    :error="$errors->first('instrument_id')"
/>
```

## Parameter

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|----------|--------------|
| `options` | Array | `[]` | Array von Optionen (meist aus Collection->toArray()) |
| `value` | Mixed | `null` | Vorausgewählter Wert |
| `name` | String | `''` | Name für das Hidden Input (für Form-Submission) |
| `placeholder` | String | `'Auswählen...'` | Placeholder Text |
| `required` | Boolean | `false` | Ob das Feld erforderlich ist |
| `display-field` | String | `'name'` | Feld für die Anzeige |
| `value-field` | String | `'id'` | Feld für den Wert |
| `search-fields` | Array | `['name']` | Felder zum Durchsuchen |
| `secondary-display-field` | String | `null` | Zusätzliches Anzeigefeld (z.B. Seriennummer) |
| `error` | String | `null` | Fehlermeldung |

## Event Handling

Die Komponente sendet Events, auf die die Parent-Komponente hören kann:

```php
// In der Parent Livewire-Komponente
protected $listeners = ['autocomplete-selected' => 'handleAutocompleteSelection'];

public function handleAutocompleteSelection($data)
{
    if ($data['name'] === 'instrument_id') {
        $this->instrument_id = $data['value'];
        // Zusätzliche Logik hier...
    }
}
```

## Funktionen

- **Typeahead/Autocomplete**: Filtert Optionen während der Eingabe
- **Keyboard Navigation**: Pfeiltasten funktionieren
- **Clear Button**: Option zum Leeren der Auswahl
- **Responsive**: Funktioniert auf verschiedenen Bildschirmgrößen
- **Validation**: Unterstützt Laravel Validation
- **Multiple Search Fields**: Kann in mehreren Feldern suchen
- **Secondary Display**: Zeigt zusätzliche Informationen unter dem Haupttext

## Beispiele

### Instrumenten-Auswahl
```php
<x-autocomplete-select 
    :options="$instruments->toArray()"
    name="instrument_id"
    placeholder="Instrument suchen..."
    :search-fields="['name', 'serial_number', 'manufacturer']"
    secondary-display-field="serial_number"
/>
```

### Benutzer-Auswahl
```php
<x-autocomplete-select 
    :options="$users->toArray()"
    name="user_id"
    placeholder="Benutzer suchen..."
    display-field="full_name"
    :search-fields="['name', 'email']"
    secondary-display-field="email"
/>
```

### Container-Auswahl
```php
<x-autocomplete-select 
    :options="$containers->toArray()"
    name="container_id"
    placeholder="Container suchen..."
    :search-fields="['name', 'barcode']"
    secondary-display-field="barcode"
/>
```

## Styling

Die Komponente verwendet Tailwind CSS Klassen. Das Styling kann durch Überschreiben der CSS-Klassen in der View angepasst werden.

## Abhängigkeiten

- Laravel Livewire
- Alpine.js (für Dropdown-Funktionalität)
- Tailwind CSS (für Styling)
