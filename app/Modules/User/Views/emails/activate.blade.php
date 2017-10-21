<div style="max-width:600px;text-align:center;background-color:lightblue">
<div style="padding-top:5px;padding-bottom:5px;width:570px">
<table style="background-color:white;margin:15px;width:570px">

    <tr>
        <th>
            Hello {{$user['first_name'] . ' ' . $user['last_name']}}
        </th>
    </tr>
<tr>
    <th style="border-bottom: 1px solid brown;padding-bottom:5px">
Greetings from Video, You are Successfully registered to our Database
</th>
</tr>
<tr><td style="padding-top:15px">
Please click on below link to activate your account. 
</td>
</tr>
<tr>
<td style="padding-top:10px">
If You are not able to click over the link, copy the link and paste the link over server.
</td>
</tr>
<tr>
<td style="padding-top:10px">
<a href="{{$url}}/auth/confirm/{{$confirmation_code}}/{{$reconfirm_code}}/{{$UUID}}">{{$url}}/auth/confirm/{{$confirmation_code}}/{{$reconfirm_code}}/{{$UUID}}</a>
</td>
</tr>
<tr>
<td style="padding-top:15px">
Regards,
</td>
</tr>
<tr>
    <td>
Video Team
</td>
</tr>
</table>
</div>
</div>