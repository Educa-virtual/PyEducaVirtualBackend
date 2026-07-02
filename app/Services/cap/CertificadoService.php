<?php

namespace App\Services\cap;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QRGdImagePNG;
use Illuminate\Support\Facades\Log;

class CertificadoService
{
    public function generarCertificado(object $data, int $iCapacitacionId, int $iPersId)
    {
        $uniqueId = hash('sha256', $data->iInscripId);
        $filename = "certificado_{$uniqueId}.pdf";

        $storagePath = "certificados/{$filename}";

        $qrData = $this->generarDatosQR($uniqueId);

        $qrBase64 = $this->generarQRBase64($qrData);
        $qrBase64 = preg_replace('/\s+/', '', $qrBase64) ?: $qrBase64;

        $html = view('cap.certificado', compact('data', 'qrBase64', $uniqueId))->render();

        $pdf = PDF::loadHTML($html)->setPaper('a4', 'landscape');

        Storage::disk('public')->put($storagePath, $pdf->output());

        return $pdf;
    }

    private function generarDatosQR(string $uniqueId): string
    {
        $baseUrl = config('app.url');
        return "{$baseUrl}/api/cap/certificado/verificar/{$uniqueId}";
    }

    private function generarQRBase64(string $data): string
    {
        $options = new QROptions([
            'outputInterface' => QRGdImagePNG::class,
            'eccLevel'        => EccLevel::M,
            'scale'           => 5,
            'outputBase64'    => true,
        ]);

        $qrcode = new QRCode($options);
        $qrBase64 = $qrcode->render($data);

        return $qrBase64;
    }
}
