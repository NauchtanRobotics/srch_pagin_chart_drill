<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$config = [
    'number' => '<li><a href="{{url}}">{{text}}</a></li>',
    'current' => '<li class="active"><a href="#">{{text}}</a></li>',
    'nextActive' => '<li><a aria-label="Next" href="{{url}}">{{text}}</a></li>',
    'nextDisabled' => '<li class="next disabled"><a aria-label="Next"><span aria-hidden="true">»</span></a></li>',
    'prevActive' => '<li><a aria-label="Previous" href="{{url}}">{{text}}</a></li>',
    'prevDisabled' => '<li class="prev disabled"><a aria-label="Previous"><span aria-hidden="true">«</span></a></li>'
];
?>