<form method="post" enctype="multipart/form-data" action="/api/v1/video/youtube/upload">
    
    @if(!empty($errors->all()))
   @foreach ($errors->all() as $error)
      <div>{{ $error }}</div>
  @endforeach
@endif
<input type="hidden" name="token" value="f0159ce0-1b1a-11e8-afac-93b99b28386a-f0159f30-1b1a-11e8-80ac-abea5dca9a03"/>
<input type="hidden" name="client_id" value="Android_4456"/>
    <table style="width:100%;border-collapse:collapse" border="1">
        <tr>
            <th>
                Name
            </th>
            <td>
                <input type="text" name="data[name]"/>
            </td>
        </tr>
        <tr>
            <th>
                Description
            </th>
            <td>
                <textarea name="data[description]"></textarea>
            </td>
        </tr>
        <tr>
            <th>
                Video
            </th>
            <td>
                <input type="file" name="file"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" >Upload</button>
        </td>
        </tr>
    </table>
</form>
