object dmMusicplayer: TdmMusicplayer
  OldCreateOrder = False
  Height = 96
  Width = 215
  object conMusic: TADOConnection
    Connected = True
    ConnectionString = 
      'Provider=Microsoft.Jet.OLEDB.4.0;User ID=Admin;Data Source=Music' +
      'player.mdb;Mode=ReadWrite;Persist Security Info=False;Jet OLEDB:' +
      'System database="";Jet OLEDB:Registry Path="";Jet OLEDB:Database' +
      ' Password="";Jet OLEDB:Engine Type=5;Jet OLEDB:Database Locking ' +
      'Mode=1;Jet OLEDB:Global Partial Bulk Ops=2;Jet OLEDB:Global Bulk' +
      ' Transactions=1;Jet OLEDB:New Database Password="";Jet OLEDB:Cre' +
      'ate System Database=False;Jet OLEDB:Encrypt Database=False;Jet O' +
      'LEDB:Don'#39't Copy Locale on Compact=False;Jet OLEDB:Compact Withou' +
      't Replica Repair=False;Jet OLEDB:SFP=False'
    LoginPrompt = False
    Mode = cmReadWrite
    Provider = 'Microsoft.Jet.OLEDB.4.0'
    Left = 152
    Top = 16
  end
  object tblMusic: TADOTable
    Active = True
    Connection = conMusic
    CursorType = ctStatic
    TableName = 'Media'
    Left = 16
    Top = 16
  end
  object dsMusic: TDataSource
    DataSet = tblMusic
    Left = 80
    Top = 16
  end
end
