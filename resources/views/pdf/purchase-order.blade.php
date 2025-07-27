<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellung {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2563eb;
        }
        
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: bold;
            padding: 4px 0;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            padding: 4px 0;
            vertical-align: top;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-requested { background: #fef3c7; color: #92400e; }
        .status-approved { background: #dbeafe; color: #1e40af; }
        .status-ordered { background: #e0e7ff; color: #5b21b6; }
        .status-shipped { background: #e0e7ff; color: #5b21b6; }
        .status-received { background: #d1fae5; color: #065f46; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        .timeline {
            margin-top: 20px;
        }
        
        .timeline-item {
            margin-bottom: 15px;
            padding-left: 25px;
            position: relative;
        }
        
        .timeline-item::before {
            content: '•';
            position: absolute;
            left: 0;
            top: 2px;
            color: #2563eb;
            font-weight: bold;
            font-size: 16px;
        }
        
        .timeline-date {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }
        
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .description {
            background: #f9fafb;
            padding: 10px;
            border-left: 4px solid #2563eb;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Instrumenten-Bestellung</h1>
        <p>{{ $order->order_number }}</p>
        <p>Erstellt am {{ $order->requested_at->format('d.m.Y H:i') }}</p>
    </div>

    <!-- Status -->
    <div class="section">
        <div class="section-title">Status</div>
        <span class="status-badge status-{{ $order->status }}">
            {{ $order->status_display }}
        </span>
    </div>

    <!-- Bestelldetails -->
    <div class="section">
        <div class="section-title">Bestelldetails</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Bestellnummer:</div>
                <div class="info-value">{{ $order->order_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Angefordert von:</div>
                <div class="info-value">{{ $order->requestedBy->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Lieferant:</div>
                <div class="info-value">{{ $order->supplier }}</div>
            </div>
            @if($order->estimated_cost)
            <div class="info-row">
                <div class="info-label">Geschätzte Kosten:</div>
                <div class="info-value">{{ number_format($order->estimated_cost, 2, ',', '.') }} €</div>
            </div>
            @endif
            @if($order->actual_cost)
            <div class="info-row">
                <div class="info-label">Tatsächliche Kosten:</div>
                <div class="info-value">{{ number_format($order->actual_cost, 2, ',', '.') }} €</div>
            </div>
            @endif
            @if($order->expected_delivery)
            <div class="info-row">
                <div class="info-label">Erwartete Lieferung:</div>
                <div class="info-value">{{ $order->expected_delivery->format('d.m.Y') }}</div>
            </div>
            @endif
        </div>
        
        @if($order->notes)
        <div>
            <strong>Notizen:</strong>
            <div class="description">{{ $order->notes }}</div>
        </div>
        @endif
    </div>

    <!-- Defektmeldung -->
    <div class="section">
        <div class="section-title">Zugehörige Defektmeldung</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Meldungsnummer:</div>
                <div class="info-value">{{ $order->defectReport->report_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Instrument:</div>
                <div class="info-value">{{ $order->defectReport->instrument->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Seriennummer:</div>
                <div class="info-value">{{ $order->defectReport->instrument->serial_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Gemeldet von:</div>
                <div class="info-value">{{ $order->defectReport->reportedBy->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Abteilung:</div>
                <div class="info-value">{{ $order->defectReport->reportingDepartment->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Defekttyp:</div>
                <div class="info-value">{{ $order->defectReport->defect_type_display }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Schweregrad:</div>
                <div class="info-value">{{ $order->defectReport->severity_display }}</div>
            </div>
        </div>
        
        <div>
            <strong>Defektbeschreibung:</strong>
            <div class="description">{{ $order->defectReport->description }}</div>
        </div>
    </div>

    <!-- Verlauf -->
    <div class="section">
        <div class="section-title">Bestellverlauf</div>
        <div class="timeline">
            <!-- Requested -->
            <div class="timeline-item">
                <strong>Bestellung angefordert</strong> von {{ $order->requestedBy->name }}
                <div class="timeline-date">{{ $order->requested_at->format('d.m.Y H:i') }}</div>
            </div>

            <!-- Approved -->
            @if($order->approved_at)
            <div class="timeline-item">
                <strong>Bestellung genehmigt</strong>
                @if($order->approvedBy)
                    von {{ $order->approvedBy->name }}
                @endif
                <div class="timeline-date">{{ $order->approved_at->format('d.m.Y H:i') }}</div>
            </div>
            @endif

            <!-- Ordered -->
            @if($order->ordered_at)
            <div class="timeline-item">
                <strong>Bestellung aufgegeben</strong>
                <div class="timeline-date">{{ $order->ordered_at->format('d.m.Y H:i') }}</div>
            </div>
            @endif

            <!-- Received -->
            @if($order->received_at)
            <div class="timeline-item">
                <strong>Bestellung erhalten</strong>
                @if($order->receivedBy)
                    von {{ $order->receivedBy->name }}
                @endif
                <div class="timeline-date">{{ $order->received_at->format('d.m.Y H:i') }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <p>Instrumenten-Management-System | Generiert am {{ now()->format('d.m.Y H:i') }}</p>
    </div>
</body>
</html>
