## RecursiveSortedIterator

A helper for the SPL recursive iterators, allowing for sorting items at each level.
Sorting the end result of an iterator (flattened using a RecursiveIteratorIterator) is easy, but the 
RecursiveSortedIterator allow sorting within each branch of the tree independently.
For example, if you are iterating directories, this will let you output directories before the files:
    
    
    $sort = new \F1\Base\RecursiveSortedIterator($filter, function($a, $b) {
        // make sure files come before directories
        if($a->isDir() && !$b->isDir())
            return 1;
        if($b->isDir() && !$a->isDir())
            return -1;
        return  strcmp($a->getFilename(), $b->getFilename());
    });