<?php

return [
    'api_key' => env('ELEVENLABS_API_KEY'),

    // Voice settings untuk bahasa Indonesia
    'voice_id' => env('ELEVENLABS_VOICE_ID', '21m00Tcm4TlvDq8ikWAM'), // Default voice (Rachel - female)
    'model_id' => env('ELEVENLABS_MODEL_ID', 'eleven_multilingual_v2'), // Multilingual model

    // Alternative voices untuk bahasa Indonesia:
    // - '21m00Tcm4TlvDq8ikWAM' (Rachel - female, clear)
    // - 'AZnzlk1XvdvUeBnXmlld' (Domi - female, calm)
    // - 'EXAVITQu4vr4xnSDxMaL' (Bella - female, expressive)
    // - 'ErXwobaYiE0JvPmMsBkB' (Antoni - male, professional)
];
