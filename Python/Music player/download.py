import json
import os
from yt_dlp import YoutubeDL

INPUT_FILE = "youtube_playlist.json"
OUTPUT_FOLDER = "audio"


class Downloader:

    def __init__(self, progress_callback=None):
        self.progress_callback = progress_callback
        self.entries = []
        self.total = 0
        self.current_index = 0
        self.current_title = ""

    def hook(self, d):

        if d["status"] == "downloading":

            percent = d.get("_percent_str", "0%").strip()

            if self.progress_callback:
                self.progress_callback(
                    self.current_index + 1,
                    self.total,
                    self.current_title,
                    percent
                )

    def start(self):

        os.makedirs(OUTPUT_FOLDER, exist_ok=True)

        ydl_opts = {
            "format": "bestaudio/best",
            "outtmpl": f"{OUTPUT_FOLDER}/%(title)s.%(ext)s",
            "quiet": True,
            "progress_hooks": [self.hook],
            "postprocessors": [
                {
                    "key": "FFmpegExtractAudio",
                    "preferredcodec": "mp3",
                    "preferredquality": "192",
                }
            ],
        }

        with YoutubeDL(ydl_opts) as ydl:

            for item in self.entries:

                url = item.get("url")
                title = item.get("name", "Unknown")

                if not url:
                    continue

                self.current_title = title

                if self.progress_callback:
                    self.progress_callback(
                        self.current_index + 1,
                        self.total,
                        title,
                        "0%"
                    )

                ydl.download([url])

                self.current_index += 1