from pathlib import Path
import json

BASE = Path(__file__).parent
AUDIO_FOLDER = BASE / "audio"
OUTPUT_FILE = BASE / "audio_index.json"

AUDIO_EXTENSIONS = {".mp3", ".wav", ".ogg", ".flac", ".m4a", ".aac"}


def scan_folder(folder: Path):

    tree = {
        "name": folder.name,
        "type": "folder",
        "children": []
    }

    try:
        items = sorted(folder.iterdir(), key=lambda x: x.name.lower())
    except Exception:
        return tree

    for item in items:

        if item.is_dir():

            tree["children"].append(scan_folder(item))

        elif item.is_file() and item.suffix.lower() in AUDIO_EXTENSIONS:

            tree["children"].append({
                "name": item.stem,
                "type": "file",
                "path": str(item.relative_to(AUDIO_FOLDER))
            })

    return tree


def extract_flat_list(tree):

    files = []

    def walk(node):

        if node["type"] == "file":
            files.append(node["path"])

        elif node["type"] == "folder":
            for child in node.get("children", []):
                walk(child)

    walk(tree)

    return files


if __name__ == "__main__":

    if not AUDIO_FOLDER.exists():
        print("Audio folder not found")
        exit()

    print("Scanning folders recursively...")

    tree = scan_folder(AUDIO_FOLDER)

    flat_files = extract_flat_list(tree)

    data = {
        "tree": tree,
        "files": flat_files
    }

    with open(OUTPUT_FILE, "w", encoding="utf-8") as f:
        json.dump(data, f, indent=4)

    print("Done")
    print(f"Found {len(flat_files)} audio files")