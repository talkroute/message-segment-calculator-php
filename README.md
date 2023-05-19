# Overview
A PHP library that enables you to calculate the message segments used for sending a message on the GSM network. A port of [Twillio's Segment Calculator](https://github.com/TwilioDevEd/message-segment-calculator).

## Installation
```shell
composer require talkroute/message-segment-calculator
```

## Usage
```php
use Talkroute\MessageSegmentCalculator\SegmentCalculator;

$message = '💡 With great power comes great responsibility.';
$segmentsUsedCount = SegmentCalculator::segmentsCount($message);

echo "The '$message' message used $segmentsUsedCount segments."
```

## 🛠️ Features
* [Smart Encoding](https://www.twilio.com/docs/messaging/services#smart-encoding) mode
* Segments calculation
* Bits calculation

### Bits calculation
```php
SegmentCalculator::bitsCount($message);
```

### Smart encoding mode
```php
SegmentCalculator::segmentsCount($message, true);
SegmentCalculator::bitsCount($message, true);
```
