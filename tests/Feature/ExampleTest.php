<?php

it('shows welcome page when not authenticated', function () {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertSee('Learn from Expert Teachers');
});
