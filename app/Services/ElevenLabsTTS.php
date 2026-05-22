<?php

namespace App\Services;

use ArdaGnsrn\ElevenLabs\ElevenLabs;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ElevenLabsTTS
{
    protected $elevenLabs;
    protected $voiceId;
    protected $modelId;

    public function __construct()
    {
        // Initialize ElevenLabs sesuai dokumentasi GitHub
        $this->elevenLabs = new ElevenLabs();

        // Voice settings untuk bahasa Indonesia - suara wanita
        $this->voiceId = config('elevenlabs.voice_id', '21m00Tcm4TlvDq8ikWAM'); // Rachel voice (female, clear)
        $this->modelId = config('elevenlabs.model_id', 'eleven_multilingual_v2'); // Multilingual model
    }

    /**
     * Generate speech untuk antrian
     * Sesuai dokumentasi: $elevenLabs->textToSpeech($voiceId, $text, $modelId, $voiceSettings)
     */
    public function generateQueueSpeech($nomorAntrian, $namaPelanggan)
    {
        try {
            // Format message untuk antrian
            $message = "Nomor antrian {$nomorAntrian}. {$namaPelanggan}, silakan masuk.";

            // Generate audio menggunakan ElevenLabs (sesuai GitHub docs)
            $response = $this->elevenLabs->textToSpeech(
                $this->voiceId,                                   // Voice ID
                $message,                                        // Text to convert
                $this->modelId,                                  // Model ID
                [                                                // Voice settings
                    'stability' => 0.5,                          // Moderate stability
                    'similarity_boost' => 0.75,                  // Good similarity
                    'style' => 0.0,                              // No style exaggeration
                    'use_speaker_boost' => true                  // Boost vocal clarity
                ]
            );

            // Generate unique filename
            $filename = 'antrian_' . Str::slug($nomorAntrian) . '_' . time() . '.mp3';

            // Save audio file ke storage (sesuai GitHub docs)
            $fullPath = storage_path('app/public/audio/' . $filename);
            $response->saveFile($fullPath);

            // Return public URL untuk audio file
            return asset('storage/audio/' . $filename);

        } catch (\Exception $e) {
            \Log::error('ElevenLabs TTS Error: ' . $e->getMessage());

            // Return fallback info jika ElevenLabs gagal
            return [
                'fallback' => true,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate speech dari custom text
     */
    public function generateSpeech($text)
    {
        try {
            $response = $this->elevenLabs->textToSpeech(
                $this->voiceId,
                $text,
                $this->modelId,
                [
                    'stability' => 0.5,
                    'similarity_boost' => 0.75,
                    'style' => 0.0,
                    'use_speaker_boost' => true
                ]
            );

            $filename = 'speech_' . Str::random(10) . '_' . time() . '.mp3';
            $fullPath = storage_path('app/public/audio/' . $filename);
            $response->saveFile($fullPath);

            return asset('storage/audio/' . $filename);

        } catch (\Exception $e) {
            \Log::error('ElevenLabs TTS Error: ' . $e->getMessage());

            return [
                'fallback' => true,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Test ElevenLabs API connection
     */
    public function testConnection()
    {
        try {
            $testText = "Halo, ini adalah test suara ElevenLabs dalam bahasa Indonesia.";

            $response = $this->elevenLabs->textToSpeech(
                $this->voiceId,
                $testText,
                $this->modelId,
                [
                    'stability' => 0.5,
                    'similarity_boost' => 0.75,
                    'style' => 0.0,
                    'use_speaker_boost' => true
                ]
            );

            $filename = 'test_' . time() . '.mp3';
            $fullPath = storage_path('app/public/audio/' . $filename);
            $response->saveFile($fullPath);

            return [
                'success' => true,
                'audio_url' => asset('storage/audio/' . $filename),
                'message' => 'ElevenLabs API berhasil dihubungi!'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'ElevenLabs API gagal dihubungi. Check API key dan koneksi.'
            ];
        }
    }

    /**
     * Generate audio dengan custom voice settings
     */
    public function generateWithSettings($text, $voiceSettings = [])
    {
        try {
            // Default settings sesuai dokumentasi GitHub
            $defaultSettings = [
                'stability' => 0.95,
                'similarity_boost' => 0.75,
                'style' => 0.06,
                'use_speaker_boost' => true
            ];

            $settings = array_merge($defaultSettings, $voiceSettings);

            $response = $this->elevenLabs->textToSpeech(
                $this->voiceId,
                $text,
                $this->modelId,
                $settings
            );

            $filename = 'custom_' . Str::random(10) . '_' . time() . '.mp3';
            $fullPath = storage_path('app/public/audio/' . $filename);
            $response->saveFile($fullPath);

            return asset('storage/audio/' . $filename);

        } catch (\Exception $e) {
            \Log::error('ElevenLabs TTS Error: ' . $e->getMessage());

            return [
                'fallback' => true,
                'error' => $e->getMessage()
            ];
        }
    }
}