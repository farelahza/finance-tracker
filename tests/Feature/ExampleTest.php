<?php

it('redirects guests to login from the homepage', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});