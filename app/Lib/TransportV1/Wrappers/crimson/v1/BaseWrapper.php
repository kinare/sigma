<?php

abstract class BaseWrapper
{
    /*
     * CATEGORY {
     *      id = Code //primary key
     *      code = Description
     *      renewal amount = Amount
     *      active = Active
     * }
     */
    //receives upstream request

    //if it is a post or update transpose to downstream format
        //requires maps to match web models to downstream models (keys, columns, optionals, defaults)


    //requires to know how to connect to downstream - implement transport

    //calling the downstream api - pass filters + request array



    //receive data from downstream

    //transpose downstream data into upstream format

    //after receiving response send data back to upstream requester
}
