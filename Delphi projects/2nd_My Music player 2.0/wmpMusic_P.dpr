program wmpMusic_P;

uses
  Vcl.Forms,
  wmpMusic_U in 'wmpMusic_U.pas' {frmMusicPlayer},
  dmMusicPlayer_U in 'dmMusicPlayer_U.pas' {dmMusicplayer: TDataModule},
  DeleteSong_U in 'DeleteSong_U.pas' {frmDelete};

{$R *.res}

begin
  Application.Initialize;
  Application.MainFormOnTaskbar := True;
  Application.CreateForm(TfrmMusicPlayer, frmMusicPlayer);
  Application.CreateForm(TdmMusicplayer, dmMusicplayer);
  Application.CreateForm(TfrmDelete, frmDelete);
  Application.Run;
end.
