program Project1;

uses
  Forms,
  Unit1 in 'Unit1.pas' {MusicPlayer},
  WMPLib_TLB in '..\..\Documents\RAD Studio\7.0\Imports\WMPLib_TLB.pas',
  Unit2 in 'Unit2.pas' {dmMediaPlayer: TDataModule};

{$R *.res}

begin
  Application.Initialize;
  Application.MainFormOnTaskbar := True;
  Application.CreateForm(TMusicPlayer, MusicPlayer);
  Application.CreateForm(TdmMediaPlayer, dmMediaPlayer);
  Application.Run;
end.
