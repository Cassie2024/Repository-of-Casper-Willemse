import re
from direct.showbase.ShowBase import ShowBase
from panda3d.core import Point3, LineSegs, NodePath, TransparencyAttrib

def extract_coordinates(gcode_file):
    coordinates = []
    last_x = last_y = last_z = 0.0

    x_pattern = re.compile(r"X([-\d.]+)")
    y_pattern = re.compile(r"Y([-\d.]+)")
    z_pattern = re.compile(r"Z([-\d.]+)")

    with open(gcode_file, 'r') as file:
        for line in file:
            x_match = x_pattern.search(line)
            y_match = y_pattern.search(line)
            z_match = z_pattern.search(line)

            if x_match:
                last_x = float(x_match.group(1))
            if y_match:
                last_y = float(y_match.group(1))
            if z_match:
                last_z = float(z_match.group(1))

            if x_match or y_match or z_match:
                coordinates.append((last_x, last_y, last_z, line.strip().startswith("G0")))

    return coordinates

class GCodeVisualizer(ShowBase):
    def __init__(self, coordinates):
        super().__init__()

        self.coordinates = coordinates

        self.draw_gcode()

    def draw_gcode(self):
        line_segs = LineSegs()
        line_segs.setThickness(2)

        for i in range(len(self.coordinates) - 1):
            p1 = Point3(self.coordinates[i][0], self.coordinates[i][1], self.coordinates[i][2])
            p2 = Point3(self.coordinates[i+1][0], self.coordinates[i+1][1], self.coordinates[i+1][2])
            is_rapid = self.coordinates[i][3]

            color = (1, 1, 1, 0.5) if is_rapid else (0, 0, 0, 1)
            line_segs.setColor(*color)
            line_segs.moveTo(p1)
            line_segs.drawTo(p2)

        node = line_segs.create()
        np = NodePath(node)
        np.setTransparency(TransparencyAttrib.MAlpha)
        np.reparentTo(self.render)

def main():
    gcode_file = 'your_gcode_file.gcode'  # Replace with your G-code file path
    coordinates = extract_coordinates(gcode_file)

    app = GCodeVisualizer(coordinates)
    app.run()

if __name__ == "__main__":
    main()