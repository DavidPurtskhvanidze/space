<object id="MediaPlayer" width="250" height="250"
    classid="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95"
    codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701"
    standby="Loading Microsoft� Windows� Media Player components..."
    type="application/x-oleobject" align="middle">

<param name="FileName" value="{$value.file_url}" />
<param name="AutoStart" value="false" />
<param name="ShowStatusBar" value="true" />
<param name="DefaultFrame" value="mainFrame" />

<embed type="application/x-mplayer2"
    autostart="0"
    pluginspage = "http://www.microsoft.com/Windows/MediaPlayer/"
    src="{$value.file_url}" align="middle" width="250" height="250"
    defaultframe="rightFrame" showstatusbar="true">
</embed>
</object>
