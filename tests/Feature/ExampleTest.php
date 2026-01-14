<?php

it('shows welcome page when not authenticated', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Learn from Expert Teachers');
});
