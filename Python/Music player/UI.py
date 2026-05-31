from __future__ import annotations

import sys
import json
import random
from pathlib import Path

from mutagen import File as MutagenFile

from PyQt6.QtCore import (
    Qt,
    QSize,
    QUrl,
    pyqtSignal,
    QTimer,
)

from PyQt6.QtGui import (
    QAction,
    QShortcut,
    QKeySequence,
    QPainter,
    QPainterPath,
    QPixmap,
)

from PyQt6.QtWidgets import (
    QApplication,
    QWidget,
    QLabel,
    QPushButton,
    QVBoxLayout,
    QHBoxLayout,
    QListWidget,
    QListWidgetItem,
    QLineEdit,
    QStackedWidget,
    QSlider,
    QScrollArea,
    QFrame,
    QSizePolicy,
    QMessageBox,
)

from PyQt6.QtMultimedia import (
    QMediaPlayer,
    QAudioOutput,
)


# ============================================================
# PATHS
# ============================================================

BASE = Path(__file__).parent
AUDIO = BASE / "audio"
DATA_FILE = BASE / "audio_index.json"
SETTINGS_FILE = BASE / "settings.json"


# ============================================================
# HELPERS
# ============================================================

def format_time(ms: int) -> str:
    seconds = int(ms / 1000)

    mins = seconds // 60
    secs = seconds % 60

    return f"{mins:02}:{secs:02}"


def safe_duration(path: Path) -> int:
    try:
        audio = MutagenFile(path)

        if audio and hasattr(audio, "info"):
            if hasattr(audio.info, "length"):
                return int(audio.info.length)

    except Exception:
        pass

    return 0


# ============================================================
# SETTINGS
# ============================================================

class SettingsManager:

    DEFAULT = {
        "volume": 0.7,
        "shuffle": False,
        "repeat": "off"
    }

    @classmethod
    def load(cls):
        try:
            if SETTINGS_FILE.exists():
                with open(SETTINGS_FILE, "r", encoding="utf-8") as f:
                    return json.load(f)
        except Exception:
            pass

        return cls.DEFAULT.copy()

    @classmethod
    def save(cls, data):
        try:
            with open(SETTINGS_FILE, "w", encoding="utf-8") as f:
                json.dump(data, f, indent=4)
        except Exception:
            pass


# ============================================================
# SONG LOADER
# ============================================================

def load_songs():

    songs = []

    if not DATA_FILE.exists():
        return songs

    try:
        with open(DATA_FILE, "r", encoding="utf-8") as f:
            data = json.load(f)

        for item in data.get("files", []):

            path = AUDIO / item

            songs.append(
                {
                    "name": Path(item).stem,
                    "path": path,
                    "duration": safe_duration(path)
                }
            )

    except Exception:
        pass

    return songs


# ============================================================
# CLICKABLE LABEL
# ============================================================

class ClickableLabel(QLabel):

    clicked = pyqtSignal()

    def mousePressEvent(self, event):
        self.clicked.emit()
        super().mousePressEvent(event)


# ============================================================
# CIRCULAR ALBUM ART
# ============================================================

class CircularArtwork(ClickableLabel):

    def __init__(self):
        super().__init__()

        self.setFixedSize(280, 280)

        self.pixmap_original = None

        self.setText("♪")
        self.setAlignment(Qt.AlignmentFlag.AlignCenter)

    def set_artwork(self, image_path=None):

        if image_path and Path(image_path).exists():
            self.pixmap_original = QPixmap(str(image_path))
        else:
            self.pixmap_original = None

        self.update()

    def paintEvent(self, event):

        painter = QPainter(self)
        painter.setRenderHint(QPainter.RenderHint.Antialiasing)

        path = QPainterPath()
        path.addEllipse(0, 0, self.width(), self.height())

        painter.setClipPath(path)

        if self.pixmap_original and not self.pixmap_original.isNull():

            pix = self.pixmap_original.scaled(
                self.size(),
                Qt.AspectRatioMode.KeepAspectRatioByExpanding,
                Qt.TransformationMode.SmoothTransformation
            )

            painter.drawPixmap(0, 0, pix)

        else:

            painter.fillPath(path, Qt.GlobalColor.darkBlue)

            painter.setPen(Qt.GlobalColor.white)

            font = self.font()
            font.setPointSize(72)

            painter.setFont(font)

            painter.drawText(
                self.rect(),
                Qt.AlignmentFlag.AlignCenter,
                "♪"
            )


# ============================================================
# LIBRARY SCREEN
# ============================================================

class LibraryScreen(QWidget):

    song_selected = pyqtSignal(int)

    def __init__(self, songs):

        super().__init__()

        self.songs = songs

        self.filtered_indices = []

        self.build_ui()

    def build_ui(self):

        layout = QVBoxLayout(self)

        title = QLabel("Library")
        title.setObjectName("title")

        self.search = QLineEdit()
        self.search.setPlaceholderText("Search songs...")
        self.search.textChanged.connect(self.filter_songs)

        self.list_widget = QListWidget()
        self.list_widget.itemClicked.connect(
            self.song_clicked
        )

        layout.addWidget(title)
        layout.addWidget(self.search)
        layout.addWidget(self.list_widget)

        self.populate()

    def populate(self):

        self.list_widget.clear()
        self.filtered_indices.clear()

        for index, song in enumerate(self.songs):

            item = QListWidgetItem(song["name"])

            self.list_widget.addItem(item)

            self.filtered_indices.append(index)

    def filter_songs(self, text):

        text = text.lower()

        self.list_widget.clear()
        self.filtered_indices.clear()

        for index, song in enumerate(self.songs):

            if text in song["name"].lower():

                self.filtered_indices.append(index)

                self.list_widget.addItem(
                    QListWidgetItem(song["name"])
                )

    def song_clicked(self, item):

        row = self.list_widget.row(item)

        if row < 0:
            return

        actual_index = self.filtered_indices[row]

        self.song_selected.emit(actual_index)

      # ============================================================
# PLAYER SCREEN
# ============================================================

class PlayerScreen(QWidget):

    back_requested = pyqtSignal()
    lyrics_requested = pyqtSignal()

    def __init__(self):

        super().__init__()

        self.build_ui()

    def build_ui(self):

        root = QVBoxLayout(self)
        root.setContentsMargins(25, 25, 25, 25)
        root.setSpacing(20)

        # -----------------------------
        # TOP BAR
        # -----------------------------

        top = QHBoxLayout()

        self.back_btn = QPushButton("← Library")
        self.back_btn.clicked.connect(
            self.back_requested.emit
        )

        top.addWidget(self.back_btn)
        top.addStretch()

        root.addLayout(top)

        # -----------------------------
        # ALBUM ART
        # -----------------------------

        self.artwork = CircularArtwork()
        self.artwork.clicked.connect(
            self.lyrics_requested.emit
        )

        art_container = QHBoxLayout()
        art_container.addStretch()
        art_container.addWidget(self.artwork)
        art_container.addStretch()

        root.addLayout(art_container)

        # -----------------------------
        # TITLE
        # -----------------------------

        self.song_title = QLabel("No Song")
        self.song_title.setObjectName("songTitle")
        self.song_title.setAlignment(
            Qt.AlignmentFlag.AlignCenter
        )

        root.addWidget(self.song_title)

        self.artist_label = QLabel("Unknown Artist")
        self.artist_label.setObjectName("artistLabel")
        self.artist_label.setAlignment(
            Qt.AlignmentFlag.AlignCenter
        )

        root.addWidget(self.artist_label)

        # -----------------------------
        # TIME
        # -----------------------------

        times = QHBoxLayout()

        self.current_time = QLabel("00:00")
        self.total_time = QLabel("00:00")

        times.addWidget(self.current_time)
        times.addStretch()
        times.addWidget(self.total_time)

        root.addLayout(times)

        # -----------------------------
        # PROGRESS
        # -----------------------------

        self.progress = QSlider(
            Qt.Orientation.Horizontal
        )

        self.progress.setRange(0, 0)

        root.addWidget(self.progress)

        # -----------------------------
        # CONTROLS
        # -----------------------------

        controls = QHBoxLayout()

        self.prev_btn = QPushButton("⏮")
        self.play_btn = QPushButton("▶")
        self.next_btn = QPushButton("⏭")

        controls.addStretch()
        controls.addWidget(self.prev_btn)
        controls.addWidget(self.play_btn)
        controls.addWidget(self.next_btn)
        controls.addStretch()

        root.addLayout(controls)

        # -----------------------------
        # SHUFFLE + REPEAT
        # -----------------------------

        modes = QHBoxLayout()

        self.shuffle_btn = QPushButton("Shuffle")
        self.repeat_btn = QPushButton("Repeat: Off")

        modes.addWidget(self.shuffle_btn)
        modes.addWidget(self.repeat_btn)

        root.addLayout(modes)

        # -----------------------------
        # VOLUME
        # -----------------------------

        volume_label = QLabel("Volume")

        self.volume_slider = QSlider(
            Qt.Orientation.Horizontal
        )

        self.volume_slider.setRange(0, 100)

        root.addWidget(volume_label)
        root.addWidget(self.volume_slider)

        root.addStretch()

    # ---------------------------------
    # UI UPDATES
    # ---------------------------------

    def set_song(self, title):

        self.song_title.setText(title)

    def set_duration(self, ms):

        self.progress.setMaximum(ms)

        self.total_time.setText(
            format_time(ms)
        )

    def set_position(self, ms):

        self.progress.blockSignals(True)
        self.progress.setValue(ms)
        self.progress.blockSignals(False)

        self.current_time.setText(
            format_time(ms)
        )


# ============================================================
# LYRICS SCREEN
# ============================================================

class LyricsScreen(QWidget):

    back_requested = pyqtSignal()

    def __init__(self):

        super().__init__()

        self.build_ui()

    def build_ui(self):

        root = QVBoxLayout(self)

        top = QHBoxLayout()

        self.back_btn = QPushButton("← Back")

        self.back_btn.clicked.connect(
            self.back_requested.emit
        )

        top.addWidget(self.back_btn)
        top.addStretch()

        root.addLayout(top)

        # -------------------------
        # ARTWORK
        # -------------------------

        self.artwork = CircularArtwork()

        art_layout = QHBoxLayout()
        art_layout.addStretch()
        art_layout.addWidget(self.artwork)
        art_layout.addStretch()

        root.addLayout(art_layout)

        # -------------------------
        # TITLE
        # -------------------------

        self.title = QLabel("Lyrics")
        self.title.setObjectName("lyricsTitle")

        self.title.setAlignment(
            Qt.AlignmentFlag.AlignCenter
        )

        root.addWidget(self.title)

        # -------------------------
        # SCROLL AREA
        # -------------------------

        self.scroll = QScrollArea()
        self.scroll.setWidgetResizable(True)

        self.lyrics_content = QLabel()

        self.lyrics_content.setWordWrap(True)

        self.lyrics_content.setAlignment(
            Qt.AlignmentFlag.AlignTop
        )

        container = QWidget()

        content_layout = QVBoxLayout(container)
        content_layout.addWidget(
            self.lyrics_content
        )
        content_layout.addStretch()

        self.scroll.setWidget(container)

        root.addWidget(self.scroll)

    def set_song_title(self, title):

        self.title.setText(title)

    def set_lyrics(self, lyrics):

        self.lyrics_content.setText(lyrics)


# ============================================================
# MAIN WINDOW
# ============================================================

class MusicPlayer(QWidget):

    def __init__(self):

        super().__init__()

        self.setWindowTitle(
            "PyQt Music Player"
        )

        self.resize(500, 900)

        # -------------------------
        # DATA
        # -------------------------

        self.songs = load_songs()

        self.current_index = -1

        self.settings_data = (
            SettingsManager.load()
        )

        self.shuffle_enabled = (
            self.settings_data.get(
                "shuffle",
                False
            )
        )

        self.repeat_mode = (
            self.settings_data.get(
                "repeat",
                "off"
            )
        )

        # -------------------------
        # AUDIO
        # -------------------------

        self.audio_output = QAudioOutput()

        self.player = QMediaPlayer()

        self.player.setAudioOutput(
            self.audio_output
        )

        # -------------------------
        # UI
        # -------------------------

        self.build_ui()

        # -------------------------
        # CONNECTIONS
        # -------------------------

        self.connect_signals()

        # -------------------------
        # LOAD SETTINGS
        # -------------------------

        self.load_saved_settings()

    def build_ui(self):

        root = QVBoxLayout(self)

        self.stack = QStackedWidget()

        self.library_screen = (
            LibraryScreen(self.songs)
        )

        self.player_screen = (
            PlayerScreen()
        )

        self.lyrics_screen = (
            LyricsScreen()
        )

        self.stack.addWidget(
            self.library_screen
        )

        self.stack.addWidget(
            self.player_screen
        )

        self.stack.addWidget(
            self.lyrics_screen
        )

        root.addWidget(self.stack)

    def connect_signals(self):

        # library

        self.library_screen.song_selected.connect(
            self.play_song
        )

        # navigation

        self.player_screen.back_requested.connect(
            lambda:
            self.stack.setCurrentWidget(
                self.library_screen
            )
        )

        self.player_screen.lyrics_requested.connect(
            self.show_lyrics
        )

        self.lyrics_screen.back_requested.connect(
            lambda:
            self.stack.setCurrentWidget(
                self.player_screen
            )
        )

        # playback controls

        self.player_screen.play_btn.clicked.connect(
            self.toggle_play
        )

        self.player_screen.next_btn.clicked.connect(
            self.next_song
        )

        self.player_screen.prev_btn.clicked.connect(
            self.prev_song
        )

        # sliders

        self.player_screen.progress.sliderMoved.connect(
            self.seek
        )

        self.player_screen.volume_slider.valueChanged.connect(
            self.volume_changed
        )

        # multimedia

        self.player.positionChanged.connect(
            self.position_changed
        )

        self.player.durationChanged.connect(
            self.duration_changed
        )

        self.player.mediaStatusChanged.connect(
            self.media_status_changed
        )      # =====================================================
    # SETTINGS
    # =====================================================

    def load_saved_settings(self):

        volume = self.settings_data.get(
            "volume",
            0.7
        )

        self.audio_output.setVolume(volume)

        self.player_screen.volume_slider.setValue(
            int(volume * 100)
        )

        self.update_shuffle_button()
        self.update_repeat_button()

    def save_settings(self):

        data = {
            "volume": self.audio_output.volume(),
            "shuffle": self.shuffle_enabled,
            "repeat": self.repeat_mode
        }

        SettingsManager.save(data)

    # =====================================================
    # PLAYBACK
    # =====================================================

    def play_song(self, index):

        if not self.songs:
            return

        if index < 0:
            return

        if index >= len(self.songs):
            return

        self.current_index = index

        song = self.songs[index]

        self.player.setSource(
            QUrl.fromLocalFile(
                str(song["path"])
            )
        )

        self.player.play()

        self.player_screen.play_btn.setText("⏸")

        self.player_screen.set_song(
            song["name"]
        )

        self.lyrics_screen.set_song_title(
            song["name"]
        )

        self.lyrics_screen.set_lyrics(
            self.generate_placeholder_lyrics(
                song["name"]
            )
        )

        self.stack.setCurrentWidget(
            self.player_screen
        )

    def toggle_play(self):

        if (
            self.player.playbackState()
            ==
            QMediaPlayer.PlaybackState.PlayingState
        ):

            self.player.pause()

            self.player_screen.play_btn.setText(
                "▶"
            )

        else:

            self.player.play()

            self.player_screen.play_btn.setText(
                "⏸"
            )

    def next_song(self):

        if not self.songs:
            return

        if self.shuffle_enabled:

            idx = random.randint(
                0,
                len(self.songs) - 1
            )

            self.play_song(idx)

            return

        idx = self.current_index + 1

        if idx >= len(self.songs):

            if self.repeat_mode == "all":
                idx = 0
            else:
                return

        self.play_song(idx)

    def prev_song(self):

        if not self.songs:
            return

        idx = self.current_index - 1

        if idx < 0:
            idx = 0

        self.play_song(idx)

    def seek(self, value):

        self.player.setPosition(value)

    # =====================================================
    # PLAYER SIGNALS
    # =====================================================

    def position_changed(self, position):

        self.player_screen.set_position(
            position
        )

    def duration_changed(self, duration):

        self.player_screen.set_duration(
            duration
        )

    def media_status_changed(self, status):

        if (
            status
            ==
            QMediaPlayer.MediaStatus.EndOfMedia
        ):

            self.handle_track_finished()

    # =====================================================
    # TRACK END
    # =====================================================

    def handle_track_finished(self):

        if self.repeat_mode == "one":

            self.play_song(
                self.current_index
            )

            return

        if self.repeat_mode == "all":

            self.next_song()

            return

        if self.repeat_mode == "off":

            if (
                self.current_index
                <
                len(self.songs) - 1
            ):
                self.next_song()
            else:
                self.player.stop()
                self.player_screen.play_btn.setText(
                    "▶"
                )

    # =====================================================
    # SHUFFLE
    # =====================================================

    def toggle_shuffle(self):

        self.shuffle_enabled = (
            not self.shuffle_enabled
        )

        self.update_shuffle_button()

        self.save_settings()

    def update_shuffle_button(self):

        if self.shuffle_enabled:

            self.player_screen.shuffle_btn.setText(
                "Shuffle: ON"
            )

        else:

            self.player_screen.shuffle_btn.setText(
                "Shuffle: OFF"
            )

    # =====================================================
    # REPEAT
    # =====================================================

    def toggle_repeat(self):

        if self.repeat_mode == "off":

            self.repeat_mode = "all"

        elif self.repeat_mode == "all":

            self.repeat_mode = "one"

        else:

            self.repeat_mode = "off"

        self.update_repeat_button()

        self.save_settings()

    def update_repeat_button(self):

        self.player_screen.repeat_btn.setText(
            f"Repeat: {self.repeat_mode.upper()}"
        )

    # =====================================================
    # VOLUME
    # =====================================================

    def volume_changed(self, value):

        volume = value / 100

        self.audio_output.setVolume(volume)

        self.save_settings()

    # =====================================================
    # LYRICS
    # =====================================================

    def show_lyrics(self):

        self.lyrics_screen.artwork.set_artwork()

        self.stack.setCurrentWidget(
            self.lyrics_screen
        )

    def generate_placeholder_lyrics(
        self,
        title
    ):

        return (
            f"{title}\n\n"
            "Lyrics unavailable.\n\n"
            "This is placeholder text.\n\n"
            "You can later load lyrics from:\n"
            "- embedded tags\n"
            "- .lrc files\n"
            "- online APIs\n\n"
            "Line 1\n"
            "Line 2\n"
            "Line 3\n"
            "Line 4\n"
            "Line 5\n"
        )

    # =====================================================
    # SHORTCUTS
    # =====================================================

    def setup_shortcuts(self):

        self.shortcut_play = QShortcut(
            QKeySequence(
                Qt.Key.Key_Space
            ),
            self
        )

        self.shortcut_next = QShortcut(
            QKeySequence(
                Qt.Key.Key_Right
            ),
            self
        )

        self.shortcut_prev = QShortcut(
            QKeySequence(
                Qt.Key.Key_Left
            ),
            self
        )

        self.shortcut_play.activated.connect(
            self.toggle_play
        )

        self.shortcut_next.activated.connect(
            self.next_song
        )

        self.shortcut_prev.activated.connect(
            self.prev_song
        )

    # =====================================================
    # STYLE
    # =====================================================

    def apply_theme(self):

        self.setStyleSheet(
            """
            QWidget{
                background:#08111f;
                color:white;
                font-size:14px;
            }

            QLabel#title{
                font-size:32px;
                font-weight:700;
                color:white;
                padding:10px;
            }

            QLabel#songTitle{
                font-size:28px;
                font-weight:700;
                color:white;
            }

            QLabel#artistLabel{
                font-size:15px;
                color:#9ca6b8;
            }

            QLabel#lyricsTitle{
                font-size:26px;
                font-weight:700;
                padding:10px;
            }

            QLineEdit{
                background:#121d2f;
                border:1px solid #2f3f60;
                border-radius:14px;
                padding:12px;
                color:white;
            }

            QListWidget{
                background:#121d2f;
                border:none;
                border-radius:18px;
                padding:10px;
            }

            QListWidget::item{
                padding:12px;
                border-radius:12px;
            }

            QListWidget::item:selected{
                background:#7c4dff;
            }

            QPushButton{
                background:#7c4dff;
                border:none;
                border-radius:16px;
                padding:10px;
                color:white;
                font-weight:600;
            }

            QPushButton:hover{
                background:#9168ff;
            }

            QSlider::groove:horizontal{
                height:8px;
                border-radius:4px;
                background:#1a2740;
            }

            QSlider::handle:horizontal{
                width:18px;
                margin:-6px 0;
                border-radius:9px;
                background:#7c4dff;
            }

            QScrollArea{
                border:none;
            }
            """
        )

    # =====================================================
    # FINAL INIT
    # =====================================================

    def finish_setup(self):

        self.setup_shortcuts()

        self.apply_theme()

        self.player_screen.shuffle_btn.clicked.connect(
            self.toggle_shuffle
        )

        self.player_screen.repeat_btn.clicked.connect(
            self.toggle_repeat
        )

        self.update_shuffle_button()
        self.update_repeat_button()


# ============================================================
# APP STARTUP PATCH
# ============================================================

_original_init = MusicPlayer.__init__


def _patched_init(self):

    _original_init(self)

    self.finish_setup()


MusicPlayer.__init__ = _patched_init


# ============================================================
# ENTRY POINT
# ============================================================

if __name__ == "__main__":

    app = QApplication(sys.argv)

    player = MusicPlayer()

    player.show()

    sys.exit(app.exec())