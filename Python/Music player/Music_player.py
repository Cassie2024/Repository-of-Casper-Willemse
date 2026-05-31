from pathlib import Path
import json
import random
import pygame
import customtkinter as ctk
from mutagen import File
import os

# =========================
# INIT
# =========================

pygame.mixer.init()

ctk.set_appearance_mode("dark")
ctk.set_default_color_theme("blue")

BASE = Path(__file__).parent
AUDIO = BASE / "audio"
DATA_FILE = BASE / "audio_index.json"
SETTINGS_FILE = BASE / "settings.json"
PLAYLIST_FILE = BASE / "playlists.json"

# =========================
# LOAD SONGS
# =========================

with open(DATA_FILE, "r", encoding="utf-8") as f:
    data = json.load(f)

songs = [
    {
        "name": Path(x).stem,
        "path": AUDIO / x
    }
    for x in data["files"]
]

# =========================
# SETTINGS
# =========================

def load_settings():
    if SETTINGS_FILE.exists():
        return json.load(open(SETTINGS_FILE))
    return {"volume": 0.7, "shuffle": False, "repeat": "off"}

settings = load_settings()

def save_settings():
    json.dump(settings, open(SETTINGS_FILE, "w"), indent=2)

# =========================
# STATE
# =========================

current_index = 0
paused = False
dragging = False
queue = []

shuffle = settings["shuffle"]
repeat = settings["repeat"]

song_length = 0
current_song = None

# =========================
# APP
# =========================

app = ctk.CTk()
app.geometry("1100x700")
app.title("Advanced Music Player")

main = ctk.CTkFrame(app)
main.pack(fill="both", expand=True)

# =========================
# HELPERS
# =========================

def format_time(s):
    m = int(s // 60)
    s = int(s % 60)
    return f"{m:02}:{s:02}"

# =========================
# CORE PLAYER
# =========================

def play_song(song):
    global current_song, song_length, paused

    current_song = song
    paused = False

    pygame.mixer.music.load(song["path"])
    pygame.mixer.music.play()

    try:
        song_length = File(song["path"]).info.length
    except:
        song_length = 0

    song_label.configure(text=song["name"])
    progress.configure(to=max(song_length, 1))
    progress.set(0)

    play_btn.configure(text="⏸")

def play_index(i):
    global current_index
    current_index = i
    play_song(songs[i])

def next_song():
    global current_index

    if queue:
        play_song(queue.pop(0))
        return

    if repeat == "one":
        play_song(songs[current_index])
        return

    if shuffle:
        current_index = random.randint(0, len(songs)-1)
    else:
        current_index += 1
        if current_index >= len(songs):
            if repeat == "all":
                current_index = 0
            else:
                return

    play_song(songs[current_index])

def prev_song():
    global current_index
    current_index = max(0, current_index - 1)
    play_song(songs[current_index])

def toggle_play():
    global paused

    if not current_song:
        return

    if paused:
        pygame.mixer.music.unpause()
        play_btn.configure(text="⏸")
    else:
        pygame.mixer.music.pause()
        play_btn.configure(text="▶")

    paused = not paused

def seek(value):
    pygame.mixer.music.play(start=float(value))

def set_volume(v):
    settings["volume"] = float(v)
    pygame.mixer.music.set_volume(float(v))
    save_settings()

def toggle_shuffle():
    global shuffle
    shuffle = not shuffle
    settings["shuffle"] = shuffle
    save_settings()

def toggle_repeat():
    global repeat
    modes = ["off", "all", "one"]
    repeat = modes[(modes.index(repeat)+1)%3]
    settings["repeat"] = repeat
    repeat_btn.configure(text=f"🔁 {repeat}")
    save_settings()

# =========================
# UI UPDATE LOOP
# =========================

def update():
    global dragging

    if pygame.mixer.music.get_busy() and not dragging:
        pos = pygame.mixer.music.get_pos()/1000
        progress.set(pos)
        time_label.configure(
            text=f"{format_time(pos)} / {format_time(song_length)}"
        )

    elif current_song and not pygame.mixer.music.get_busy():
        next_song()

    app.after(500, update)

# =========================
# UI
# =========================

left = ctk.CTkFrame(main, width=300)
left.pack(side="left", fill="y")

right = ctk.CTkFrame(main)
right.pack(side="left", fill="both", expand=True)

search = ctk.CTkEntry(left, placeholder_text="Search")
search.pack(fill="x", padx=10, pady=10)

song_buttons = []

def build_list(q=""):
    for b in song_buttons:
        b.destroy()
    song_buttons.clear()

    for i, s in enumerate(songs):
        if q.lower() not in s["name"].lower():
            continue

        b = ctk.CTkButton(
            left,
            text=s["name"],
            command=lambda i=i: play_index(i)
        )
        b.pack(fill="x", padx=5, pady=2)
        song_buttons.append(b)

search.bind("<KeyRelease>", lambda e: build_list(search.get()))

song_label = ctk.CTkLabel(right, text="No Song", font=("Arial", 24))
song_label.pack(pady=20)

time_label = ctk.CTkLabel(right, text="00:00 / 00:00")
time_label.pack()

progress = ctk.CTkSlider(right, from_=0, to=1, command=seek)
progress.pack(fill="x", padx=50, pady=10)

progress.bind("<ButtonPress-1>", lambda e: None)

controls = ctk.CTkFrame(right)
controls.pack(pady=20)

ctk.CTkButton(controls, text="⏮", command=prev_song).grid(row=0, column=0, padx=5)
play_btn = ctk.CTkButton(controls, text="▶", command=toggle_play)
play_btn.grid(row=0, column=1, padx=5)
ctk.CTkButton(controls, text="⏭", command=next_song).grid(row=0, column=2, padx=5)

shuffle_btn = ctk.CTkButton(controls, text="🔀", command=toggle_shuffle)
shuffle_btn.grid(row=0, column=3, padx=5)

repeat_btn = ctk.CTkButton(controls, text="🔁 off", command=toggle_repeat)
repeat_btn.grid(row=0, column=4, padx=5)

vol = ctk.CTkSlider(right, from_=0, to=1, command=set_volume)
vol.set(settings["volume"])
vol.pack(fill="x", padx=100, pady=10)

# =========================
# SHORTCUTS
# =========================

app.bind("<space>", lambda e: toggle_play())
app.bind("<Right>", lambda e: next_song())
app.bind("<Left>", lambda e: prev_song())

# =========================
# START
# =========================

build_list()
update()

pygame.mixer.music.set_volume(settings["volume"])

app.mainloop()