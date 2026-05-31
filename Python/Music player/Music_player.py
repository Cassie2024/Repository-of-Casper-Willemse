from pathlib import Path
import json
import pygame
import customtkinter as ctk
from mutagen import File

# --------------------
# Setup
# --------------------

pygame.mixer.init()

script_dir = Path(__file__).parent
audio_folder = script_dir / "audio"
json_file = script_dir / "audio_index.json"

with open(json_file, "r", encoding="utf-8") as f:
    data = json.load(f)

songs = []

for file_path in data["files"]:
    path = Path(file_path)

    songs.append({
        "name": path.stem,
        "path": audio_folder / file_path
    })

# --------------------
# UI
# --------------------

ctk.set_appearance_mode("dark")
app = ctk.CTk()
app.geometry("700x500")
app.title("Music Player")

current_song = None
song_length = 0
dragging = False

# --------------------
# Functions
# --------------------

def format_time(seconds):
    mins = int(seconds // 60)
    secs = int(seconds % 60)
    return f"{mins:02}:{secs:02}"


def play_song(song):
    global current_song, song_length

    current_song = song

    pygame.mixer.music.load(song["path"])
    pygame.mixer.music.play()

    audio_info = File(song["path"])
    song_length = audio_info.info.length

    song_label.configure(text=song["name"])
    duration_label.configure(
        text=f"00:00 / {format_time(song_length)}"
    )


def select_song(name):
    for song in songs:
        if song["name"] == name:
            play_song(song)
            break


def update_progress():
    global dragging

    if pygame.mixer.music.get_busy() and not dragging:
        pos = pygame.mixer.music.get_pos() / 1000

        progress_slider.set(pos)

        duration_label.configure(
            text=f"{format_time(pos)} / {format_time(song_length)}"
        )

    app.after(500, update_progress)


def seek(value):
    global dragging

    if current_song is None:
        return

    pygame.mixer.music.play(start=float(value))
    dragging = False


def slider_pressed(event):
    global dragging
    dragging = True


# --------------------
# Layout
# --------------------

left_frame = ctk.CTkFrame(app)
left_frame.pack(side="left", fill="both", expand=True, padx=10, pady=10)

song_label = ctk.CTkLabel(
    app,
    text="No Song Selected",
    font=("Arial", 20)
)
song_label.pack(pady=(20, 10))

duration_label = ctk.CTkLabel(
    app,
    text="00:00 / 00:00"
)
duration_label.pack()

progress_slider = ctk.CTkSlider(
    app,
    from_=0,
    to=300,
    command=seek
)
progress_slider.pack(fill="x", padx=20, pady=10)

progress_slider.bind("<ButtonPress-1>", slider_pressed)

# Song list

for song in songs:
    btn = ctk.CTkButton(
        left_frame,
        text=song["name"],
        command=lambda n=song["name"]: select_song(n)
    )
    btn.pack(fill="x", pady=2)

# --------------------
# Start
# --------------------

update_progress()
app.mainloop()