# Audio Instructions for Sistem Antrian

## Current Status: ✅ WORKING WITH FALLBACK

The system currently uses a **Web Audio API fallback** that generates a dingdong sound programmatically. This means the system works **WITHOUT** needing an actual MP3 file.

## Optional: Add Custom Dingdong MP3

If you want to use a real dingdong sound effect instead of the generated one:

### Option 1: Free Sound Effects
1. Visit one of these websites:
   - [Freesound.org](https://freesound.org/) (search: "dingdong")
   - [Zapsplat.com](https://www.zapsplat.com/) (free account required)
   - [SoundBible.com](https://soundbible.com/) (search: "doorbell")

2. Download a short dingdong sound (< 3 seconds)
3. Save the file as `dingdong.mp3` in this `public/` directory
4. The system will automatically use the MP3 file instead of the fallback

### Option 2: Record Your Own
1. Use a voice recorder app on your phone
2. Record a dingdong sound (or use a physical doorbell)
3. Transfer the recording to your computer
4. Convert to MP3 format if needed
5. Save as `dingdong.mp3` in this `public/` directory

### Option 3: Use Online Audio Tools
1. Visit [BeepGenerator.com](https://www.beepgenerator.com/) or similar
2. Create a custom dingdong sound
3. Download as MP3
4. Save as `dingdong.mp3` in this `public/` directory

## Testing the Audio

1. Start the Laravel server: `php artisan serve`
2. Open the browser to: `http://127.0.0.1:8000`
3. Click the "🔊 Enable Audio & Notifikasi" button
4. You should hear a test sound (either MP3 or fallback)
5. When a new queue is called, the system will:
   - Play dingdong sound
   - Announce: "Nomor antrian X. [NAMA], silakan masuk."

## Technical Details

- **Fallback**: Web Audio API generates sine wave sounds
- **Speech**: Web Speech API for Indonesian text-to-speech
- **Browser Support**: Chrome, Firefox, Edge (all modern browsers)
- **User Gesture Required**: Audio must be enabled by clicking the button first

## Troubleshooting

**No sound plays:**
- Make sure you clicked "Enable Audio & Notifikasi" first
- Check browser volume settings
- Check if browser allows audio autoplay

**Sound is too loud/quiet:**
- Adjust the `volume` values in `papan.blade.php` (search for `volume: 0.3` or `volume: 0.5`)

**Speech doesn't work:**
- Check browser supports Web Speech API
- Try Chrome/Edge for best Indonesian language support
- Check `lang = 'id-ID'` is supported in your browser