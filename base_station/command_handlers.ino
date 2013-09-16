  /********************************************************************************
  INSERT: Code to handle numeric command codes from command_1, command_2, command_3
  *********************************************************************************/
  
  void command_1_handler(void)
  {
    //example -- lets you specify a different poll interval in seconds
    if (command_1 > 0)
      {
        long newInterval = command_1;
        pollInterval = newInterval * 1000L;  //example
      }
  }
  
  void command_2_handler(void)
  {
        //example
    if (command_2 == 1)
      {
         //turn a device on
      }
   else if (command_2 == -1)
      {
        //turn a device off
      } 
  }
  
  void command_3_handler(void)
  {
        //example
    if (command_3 == 0)
      {
        //continue to do something -- 0 is the default value
      }
   else if (command_3 == 1)
      {
        //do something else
      }
   else if (command_3 == 99)
      {
        //do a third thing
      }  
  }
