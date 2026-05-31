from yt_dlp import YoutubeDL
import json


class YTPlaylistExtractor:

    def __init__(self, playlist_url: str):
        self.playlist_url = playlist_url
        self.songs = []

    def extract(self):
        if not self.playlist_url:
            raise ValueError("Playlist URL is empty")

        ydl_opts = {
            "quiet": True,
            "extract_flat": True,
            "skip_download": True,
        }

        with YoutubeDL(ydl_opts) as ydl:
            info = ydl.extract_info(self.playlist_url, download=False)

            for entry in info.get("entries", []):
                if not entry:
                    continue

                video_id = entry.get("id") or entry.get("url")
                title = entry.get("title", "Unknown")

                if not video_id:
                    continue

                if "http" in str(video_id):
                    url = video_id
                else:
                    url = f"https://www.youtube.com/watch?v={video_id}"

                self.songs.append({
                    "name": title,
                    "url": url
                })

        return self.songs

    def save_json(self, filename="youtube_playlist.json"):

        data = {
            "source": self.playlist_url,
            "files": self.songs
        }

        with open(filename, "w", encoding="utf-8") as f:
            json.dump(data, f, indent=4, ensure_ascii=False)

        return len(self.songs)