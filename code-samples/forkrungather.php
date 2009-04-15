<?php

  // create a subdirectory in temp to store results

  $temp = sys_get_temp_dir();
  $dir = $temp . '/runtests.' . md5(uniqid());

  if (!file_exists($dir))
  {
    mkdir($dir);
  }

  var_dump('master process ' . getmypid());

  // run five test processes concurrently
  run_tests(5, $dir);


  // collect results from subdirectory in temp

  $result = array();

  foreach (new DirectoryIterator($dir) as $item)
  {
    // skip any subdirectories, including . and ..
    if ($item->isFile())
    {
      $result[] = file_get_contents($item->getPathname());
    }
  }

  var_dump($result);
  var_dump('master ' . getmypid() . ' done');


  // run $aNumberOfProcesses test processes in parallel
  // store results to $aResultDir, a subdirectory of temp.

  function run_tests($aNumberOfProcesses, $aResultDir)
  {
    $pids = array();

    for ($i = 0; $i < $aNumberOfProcesses; $i++)
    {
      $pid = pcntl_fork();

      if ($pid == -1)
      {
        throw new Exception('Could not fork process');
      }

      if ($pid != 0)
      {
        // remember the pids of all forked processes
        $pids[] = $pid;
      } else {
        // run the tests, store results in subdirectory in temp
        run_test($aResultDir);
        // terminate this child process
        exit;
      }
    }

    // wait for all child processes to terminate
    // (note: this loop does not care about the order in which child
    // processes terminate).

    foreach ($pids as $pid)
    {
      pcntl_waitpid($pid, $status);
    }
  }


  // run a single test, storing result to subdirectory in temp

  function run_test($aResultDir)
  {
    // create a random result
    $result = rand(3, 8);

    // output result for comparison with final result
    var_dump('test process ' . getmypid() . ' result ' . $result);

    // random delay
    sleep($result);

    // store result for master process to gather later
    file_put_contents($aResultDir . '/' . getmypid(), $result);
  }

?>