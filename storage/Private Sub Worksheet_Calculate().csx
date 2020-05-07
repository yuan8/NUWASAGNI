Private Sub Worksheet_Calculate()

    Dim TGG As Range
    Set TGG = Range("G11:G558")
    
    Debug.Print "RUN_CAL"
  
    Dim rng As Range, clr As String
    
    For Each rng In TGG
        Debug.Print rng
        If Left(rng.Value2, 1) = "#" Then
            If Len(rng.Value2) = 7 Then
        
                clr = Mid(rng.Value2, 2, 7)
                rng.Interior.Color = _
                  RGB(Application.Hex2Dec(Left(clr, 2)), _
                      Application.Hex2Dec(Mid(clr, 3, 2)), _
                      Application.Hex2Dec(Right(clr, 2)))
            Else
                clr = "ffffff"
                rng.Interior.Color = _
                  RGB(Application.Hex2Dec(Left(clr, 2)), _
                      Application.Hex2Dec(Mid(clr, 3, 2)), _
                      Application.Hex2Dec(Right(clr, 2)))
            
            End If
        End If
    Next rng


End Sub

Private Sub Worksheet_Change(ByVal Target As Range)
    On Error GoTo bm_Safe_Exit
    Application.EnableEvents = False
    Dim rng As Range, clr As String
    For Each rng In Target
        If Left(rng.Value2, 1) = "#" Then
            If Len(rng.Value2) = 7 Then
           
                clr = Mid(rng.Value2, 2, 7)
                rng.Interior.Color = _
                  RGB(Application.Hex2Dec(Left(clr, 2)), _
                      Application.Hex2Dec(Mid(clr, 3, 2)), _
                      Application.Hex2Dec(Right(clr, 2)))
            Else
                clr = "ffffff"
                rng.Interior.Color = _
                  RGB(Application.Hex2Dec(Left(clr, 2)), _
                      Application.Hex2Dec(Mid(clr, 3, 2)), _
                      Application.Hex2Dec(Right(clr, 2)))
            
            End If
        End If
    Next rng

bm_Safe_Exit:
    Application.EnableEvents = True
End Sub

Private Sub Worksheet_SelectionChange(ByVal Target As Range)

End Sub
