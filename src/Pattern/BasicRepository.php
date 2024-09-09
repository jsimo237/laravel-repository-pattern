<?php


namespace Jsimo\LaravelRepositoryPattern\Pattern;


abstract class BasicRepository implements BaseRepositoryInterface {

    use Validable,ExecuteProcessInTransaction;
}
