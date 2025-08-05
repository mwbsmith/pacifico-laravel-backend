<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #495057;
        }
        .value {
            margin-top: 5px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>New Contact Message - Pacifico Internacional</h2>
        <p>You have received a new message through your website contact form.</p>
    </div>

    <div class="content">
        <div class="field">
            <div class="label">Name:</div>
            <div class="value">{{ $messageData->name }}</div>
        </div>

        <div class="field">
            <div class="label">Email:</div>
            <div class="value">{{ $messageData->email }}</div>
        </div>

        @if($messageData->phone)
        <div class="field">
            <div class="label">Phone:</div>
            <div class="value">{{ $messageData->phone }}</div>
        </div>
        @endif

        <div class="field">
            <div class="label">Message:</div>
            <div class="value">{{ $messageData->message }}</div>
        </div>

        <div class="field">
            <div class="label">Sent At:</div>
            <div class="value">{{ $messageData->sent_at->format('F j, Y \a\t g:i A') }}</div>
        </div>
    </div>

    <p style="margin-top: 20px; font-size: 12px; color: #6c757d;">
        This message was sent from the Pacifico Internacional website contact form.
    </p>
</body>
</html>