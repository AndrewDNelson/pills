## Quickdose

Quickdose is an integration with hardware and software. It is a pill dispenser with an ESP32 microcontroller. It fetches a schedule from the internet, and dispenses accordingly, uploading dose info once taken. The schedule can be updated with the [Web app](https://quickdose.tech/), where doses can be viewed and pill count left as well.

## Technology

There are a few components to the project.
- Clientside ESP32 Micropython code
- AWS Lambda serving a PHP [Laravel](https://laravel.com/docs) application
- AWS IoT core communicating with the ESP32 and the server, being the middleman.

## Repository structure

This repo is the Laravel project files, but with an additional folder *device*. That contains all the files that are uploaded to the microcontroller.
