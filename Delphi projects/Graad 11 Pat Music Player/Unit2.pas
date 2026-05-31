unit Unit2;

interface

uses
  SysUtils, Classes, DB, ADODB, Dialogs;

type
  TdmMediaPlayer = class(TDataModule)
    ADOConnection1: TADOConnection;
    tblMedia: TADOTable;
    DataSource1: TDataSource;
    OpenDialog1: TOpenDialog;
  private
    { Private declarations }
  public
    { Public declarations }
  end;

var
  dmMediaPlayer: TdmMediaPlayer;

implementation

{$R *.dfm}

end.
