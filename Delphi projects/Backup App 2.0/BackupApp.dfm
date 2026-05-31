object Form2: TForm2
  Left = 0
  Top = 0
  Caption = 'Form2'
  ClientHeight = 434
  ClientWidth = 975
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -12
  Font.Name = 'Segoe UI'
  Font.Style = []
  OnActivate = FormActivate
  TextHeight = 15
  object RedOut: TRichEdit
    Left = 0
    Top = 59
    Width = 611
    Height = 375
    Font.Charset = ANSI_CHARSET
    Font.Color = clWindowText
    Font.Height = -12
    Font.Name = 'Segoe UI'
    Font.Style = []
    ParentFont = False
    TabOrder = 0
  end
  object BtnCopy: TButton
    Left = 617
    Top = 59
    Width = 218
    Height = 58
    Caption = 'Copy'
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = 34
    Font.Name = 'Segoe UI'
    Font.Style = []
    ParentFont = False
    TabOrder = 1
    OnClick = BtnCopyClick
  end
  object btnAuto: TButton
    Left = 841
    Top = 59
    Width = 130
    Height = 58
    Caption = 'Auto'
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = 34
    Font.Name = 'Segoe UI'
    Font.Style = []
    ParentFont = False
    TabOrder = 2
    OnClick = btnAutoClick
  end
  object btnchange: TButton
    Left = 753
    Top = 122
    Width = 218
    Height = 58
    Caption = 'Change Directory'
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = 34
    Font.Name = 'Segoe UI'
    Font.Style = []
    ParentFont = False
    TabOrder = 3
    OnClick = btnchangeClick
  end
  object btnBackup: TButton
    Left = 617
    Top = 122
    Width = 130
    Height = 58
    Caption = 'Backup'
    Font.Charset = DEFAULT_CHARSET
    Font.Color = clWindowText
    Font.Height = 34
    Font.Name = 'Segoe UI'
    Font.Style = []
    ParentFont = False
    TabOrder = 4
    OnClick = btnBackupClick
  end
  object Panel1: TPanel
    Left = 617
    Top = 186
    Width = 354
    Height = 252
    BevelInner = bvSpace
    BevelKind = bkTile
    BevelOuter = bvLowered
    TabOrder = 5
    object Image1: TImage
      Left = 16
      Top = 16
      Width = 321
      Height = 217
    end
  end
  object pnlTop: TPanel
    Left = 0
    Top = 8
    Width = 971
    Height = 45
    BevelInner = bvSpace
    BevelKind = bkTile
    BevelOuter = bvLowered
    TabOrder = 6
    object lblPrgname: TLabel
      Left = 193
      Top = 7
      Width = 183
      Height = 23
      Caption = 'Game Save Bachup App'
      Font.Charset = DEFAULT_CHARSET
      Font.Color = clWindowText
      Font.Height = 24
      Font.Name = 'Segoe UI'
      Font.Style = []
      ParentFont = False
    end
    object lblTimer: TLabel
      Left = 633
      Top = 13
      Width = 29
      Height = 15
      Caption = 'Time:'
    end
  end
  object Timer1: TTimer
    OnTimer = Timer1Timer
    Left = 8
    Top = 64
  end
  object OpenDialog1: TOpenDialog
    Left = 8
    Top = 120
  end
end
