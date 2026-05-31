unit DeleteSong_U;

interface

uses
  Winapi.Windows, Winapi.Messages, System.SysUtils, System.Variants, System.Classes, Vcl.Graphics,
  Vcl.Controls, Vcl.Forms, Vcl.Dialogs, dmMusicPlayer_U, Data.DB, Vcl.ExtCtrls,
  Vcl.Grids, Vcl.DBGrids, Vcl.StdCtrls;

type
  TfrmDelete = class(TForm)
    DBGrid1: TDBGrid;
    Panel1: TPanel;
    btnDelete: TButton;
    procedure btnDeleteClick(Sender: TObject);
  private
    { Private declarations }
  public
    { Public declarations }
  end;

var
  frmDelete: TfrmDelete;

implementation

{$R *.dfm}

procedure TfrmDelete.btnDeleteClick(Sender: TObject);
begin
   with dmMusicPlayer Do
       Begin
          tblMusic.Delete;
       End;
end;

end.
