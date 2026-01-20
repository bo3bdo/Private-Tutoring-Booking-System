@extends('errors::minimal')

@section('title', __('common.Forbidden'))
@section('code', '403')
@section('message', __('common.Forbidden'))
@section('description', __('common.You do not have permission to access this resource.'))
