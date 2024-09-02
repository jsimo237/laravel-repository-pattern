<?php


namespace Jsimo\LaravelRepositoryPattern\Repositories;


abstract class RepositoryActionType {

    const CREATE = "CREATE";
    const UPDATE = "UPDATE";
    const DELETE = "DELETE";
}