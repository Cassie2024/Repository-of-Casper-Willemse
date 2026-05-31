import sys

from PyQt6.QtWidgets import (
    QApplication,
    QWidget,
    QVBoxLayout,
    QLineEdit,
    QPushButton,
    QLabel,
    QMessageBox,
    QProgressBar
)

from ytdl import YTPlaylistExtractor
from download import Downloader


# ---------------- DOWNLOAD WINDOW ----------------

class DownloadWindow(QWidget):

    def __init__(self, total):
        super().__init__()

        self.setWindowTitle("Downloading Playlist")
        self.setFixedSize(420, 180)

        self.total = total

        layout = QVBoxLayout(self)

        self.song_label = QLabel("Waiting...")
        layout.addWidget(self.song_label)

        self.progress = QProgressBar()
        self.progress.setRange(0, 100)
        layout.addWidget(self.progress)

        self.status_label = QLabel("0 / 0")
        layout.addWidget(self.status_label)

    def update_progress(self, index, total, title, percent):

        self.song_label.setText(title)
        self.status_label.setText(f"{index} / {total}")

        try:
            self.progress.setValue(int(float(percent.replace("%", ""))))
        except:
            pass


# ---------------- MAIN APP ----------------

class PlaylistApp(QWidget):

    def __init__(self):
        super().__init__()

        self.setWindowTitle("YouTube Playlist Loader")
        self.setFixedSize(500, 250)

        self.extractor = None
        self.download_window = None

        self.setup_ui()

    def setup_ui(self):

        self.layout = QVBoxLayout(self)

        self.label = QLabel("Paste YouTube Playlist Link:")
        self.layout.addWidget(self.label)

        self.input = QLineEdit()
        self.layout.addWidget(self.input)

        self.process_btn = QPushButton("Process Playlist")
        self.process_btn.clicked.connect(self.process_playlist)
        self.layout.addWidget(self.process_btn)

        self.download_btn = QPushButton("Download Playlist")
        self.download_btn.setEnabled(False)
        self.download_btn.clicked.connect(self.start_download)
        self.layout.addWidget(self.download_btn)

    def process_playlist(self):

        url = self.input.text().strip()

        if not url:
            QMessageBox.warning(self, "Error", "No URL provided")
            return

        try:
            self.extractor = YTPlaylistExtractor(url)
            self.extractor.extract()
            self.extractor.save_json()

            self.download_btn.setEnabled(True)

            QMessageBox.information(self, "Done", "Playlist ready")

        except Exception as e:
            QMessageBox.critical(self, "Error", str(e))

    def start_download(self):

        entries = self.extractor.songs

        self.download_window = DownloadWindow(len(entries))
        self.download_window.show()

        def callback(index, total, title, percent):
            self.download_window.update_progress(index, total, title, percent)

        downloader = Downloader(progress_callback=callback)
        downloader.entries = entries
        downloader.total = len(entries)

        downloader.start()


if __name__ == "__main__":

    app = QApplication(sys.argv)
    window = PlaylistApp()
    window.show()
    sys.exit(app.exec())