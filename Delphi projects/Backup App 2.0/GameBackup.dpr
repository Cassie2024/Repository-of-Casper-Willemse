program GameBackup;

uses
  Vcl.Forms,
  BackupApp in 'BackupApp.pas' {Form2},
  Vcl.Themes,
  Vcl.Styles;

{$R *.res}

begin
  Application.Initialize;
  Application.MainFormOnTaskbar := True;
  TStyleManager.TrySetStyle('Ruby Graphite');
  Application.CreateForm(TForm2, Form2);
  Application.Run;
end.
