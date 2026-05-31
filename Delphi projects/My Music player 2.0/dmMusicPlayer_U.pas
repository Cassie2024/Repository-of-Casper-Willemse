unit dmMusicPlayer_U;

interface

uses
  System.SysUtils, System.Classes, Data.DB, Data.Win.ADODB;

type
  TdmMusicplayer = class(TDataModule)
    conMusic: TADOConnection;
    tblMusic: TADOTable;
    dsMusic: TDataSource;
  private
    { Private declarations }
  public
    { Public declarations }
  end;

var
  dmMusicplayer: TdmMusicplayer;

implementation

{%CLASSGROUP 'Vcl.Controls.TControl'}

{$R *.dfm}

end.
