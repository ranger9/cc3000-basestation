    /***********************************************************************
    INSERT: Code to read data into variables data_1, data_2, and data_3
    ************************************************************************/
    
    void data_1_handler(void)
    {
     //insert your sensor-reading routines here for reading data
     //that you will report on the data_1 channel
     
     //for this example, we are reporting the current poll interval in seconds
     data_1 = pollInterval/1000;
    }
    
    void data_2_handler(void)
    {
     //insert your sensor-reading routines here for reading data
     //that you will report on the data_2 channel
    }
    
    void data_3_handler(void)
    {
     //insert your sensor-reading routines here for reading data
     //that you will report on the data_3 channel
    }
