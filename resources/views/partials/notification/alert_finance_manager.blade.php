<?php
/**
 * Created by PhpStorm for WhelsonFuelSystem
 * User: Vincent Guyo
 * Date: 7/1/2020
 * Time: 10:14 AM
 */
?>
<li class="dropdown-item notify-item" id="markasread" onclick="markNotificationAsRead()">
    <a href="{{route('manage.requests')}}">{{$notification->data['request_count']}} requests need your approval.</a>
</li>
