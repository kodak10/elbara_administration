<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class QrCodeService
{
    public function generateForOrder($orderId, $paymentUrl)
    {
        try {
            // Générer le QR code sous forme de données brutes
            $qrCodeData = QrCode::format('png')
                ->size(300)
                ->errorCorrection('H') // Niveau élevé de correction d'erreur
                ->generate($paymentUrl);

            // Nom du fichier avec horodatage
            $fileName = "qrcodes/order_{$orderId}_" . time() . '.png';

            // Stocker le QR code dans le disque 'public'
            Storage::disk('public')->put($fileName, $qrCodeData);

            // Vérifier si le fichier a bien été créé
            if (!Storage::disk('public')->exists($fileName)) {
                Log::error("Échec de la sauvegarde du QR Code : {$fileName}");
                return null; // Retourner null en cas d'échec
            }

            return $fileName;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du QR Code: ' . $e->getMessage());
            return null; // Retourner null en cas d'erreur
        }
    }
}