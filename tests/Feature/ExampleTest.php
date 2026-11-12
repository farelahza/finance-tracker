<?php

it('displays the welcome page on the homepage', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertViewIs('welcome');
});