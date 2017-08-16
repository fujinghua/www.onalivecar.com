1.使用 PHPExcel_IOFactory 读取文件
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
2.使用一个特定的读取类，读取文件
$objReader = new PHPExcel_Reader_Excel5();
objPHPExcel = $objReader->load($inputFileName);
3.使用 PHPExcel_IOFactory 创建一个特定的读取类
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($inputFileName);
读取类型有：
$inputFileType = 'Excel5';
$inputFileType = 'Excel2007';
$inputFileType = 'Excel2003XML';
$inputFileType = 'OOCalc';
$inputFileType = 'SYLK';
$inputFileType = 'Gnumeric';
$inputFileType = 'CSV';


4.使用 PHPExcel_IOFactory 来鉴别文件应该使用哪一个读取类
$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($inputFileName);
5.只读去数据，忽略里面各种格式等(对于Excel读去，有很大优化)
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($inputFileName);
6.加载Excel所有的工作表
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objReader->setLoadAllSheets();      // 加载所有的工作表
$objPHPExcel = $objReader->load($inputFileName);
$objPHPExcel->getSheetCount();       // 获取工作表的个数
$objPHPExcel->getSheetNames();       // 获取所有工作表的名字数组
7.加载单个的命名的工作表
$sheetname = 'Data Sheet #2';       // 单个工作表，传入字符串
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objReader->setLoadSheetsOnly($sheetname);       // 加载单个工作表，传入工作表名字(例如：'Data Sheet #2')
$objPHPExcel = $objReader->load($inputFileName);
8.加载多个命名的工作表
$sheetnames = array('Data Sheet #1', 'Data Sheet #2');      // 多个工作表，传入数组
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objReader->setLoadSheetsOnly($sheetnames);      // 加载多个工作表，传入工作表名字数组
$objPHPExcel = $objReader->load($inputFileName);
9.自定义一个读去过滤器
class MyReadFilter implements PHPExcel_Reader_IReadFilter
{
public function readCell($column, $row, $worksheetName = '') {


// 只读去1-7行&A－E列中的单元格
if ($row >= 1 && $row <= 7) {
if (in_array($column,range('A','E'))) {
return true;
}
}
return false;
}
}
$filterSubset = new MyReadFilter();
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objReader->setReadFilter($filterSubset);        // 设置实例化的过滤器对象
$objPHPExcel = $objReader->load($inputFileName);
10.同样是自定义一个读去过滤器，但可配置读去的行和列范围
class MyReadFilter implements PHPExcel_Reader_IReadFilter
{
private $_startRow = 0;     // 开始行
private $_endRow = 0;       // 结束行
private $_columns = array();    // 列跨度
public function __construct($startRow, $endRow, $columns) {
$this->_startRow = $startRow;
$this->_endRow       = $endRow;
$this->_columns      = $columns;
}
public function readCell($column, $row, $worksheetName = '') {
if ($row >= $this->_startRow && $row <= $this->_endRow) {
if (in_array($column,$this->_columns)) {
return true;
}
}
return false;
}
}
$filterSubset = new MyReadFilter(9,15,range('G','K'));
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objReader->setReadFilter($filterSubset);        // 设置实例化的过滤器对象
$objPHPExcel = $objReader->load($inputFileName);
11.分块读取Excel，原理还是：自定义读取过滤器
class chunkReadFilter implements PHPExcel_Reader_IReadFilter
{
private $_startRow = 0;     // 开始行
private $_endRow = 0;       // 结束行
public function __construct($startRow, $chunkSize) {    // 我们需要传递：开始行号&行跨度(来计算结束行号)
$this->_startRow = $startRow;
$this->_endRow       = $startRow + $chunkSize;
}
public function readCell($column, $row, $worksheetName = '') {
if (($row == 1) || ($row >= $this->_startRow && $row < $this->_endRow)) {
return true;
}
return false;
}
}
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$chunkSize = 20;    // 定义每块读去的行数


// 就可在一个循环中，多次读去块，而不用一次性将整个Excel表读入到内存中
for ($startRow = 2; $startRow <= 240; $startRow += $chunkSize) {
$chunkFilter = new chunkReadFilter($startRow, $chunkSize);
$objReader->setReadFilter($chunkFilter); // 设置实例化的过滤器对象
$objPHPExcel = $objReader->load($inputFileName);
// 开始读取每行数据，并插入到数据库
}
12.分块读取Excel的第2个版本
class chunkReadFilter implements PHPExcel_Reader_IReadFilter
{
private $_startRow = 0;     // 开始行
private $_endRow = 0;       // 结束行


// 定义了一个读去指定范围行的方法
public function setRows($startRow, $chunkSize) {
$this->_startRow = $startRow;
$this->_endRow       = $startRow + $chunkSize;
}
public function readCell($column, $row, $worksheetName = '') {
if (($row == 1) || ($row >= $this->_startRow && $row < $this->_endRow)) {
return true;
}
return false;
}
}
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$chunkSize = 20;    // 定义每块读去的行数


// 在循环外部，实例化过滤器类，而不用循环内每次实例化(应该更优化)
$chunkFilter = new chunkReadFilter();
$objReader->setReadFilter($chunkFilter);
for ($startRow = 2; $startRow <= 240; $startRow += $chunkSize) {


// 循环内部，使用实例化的对象的方法，来调整读取的行范围即可
$chunkFilter->setRows($startRow,$chunkSize);
$objPHPExcel = $objReader->load($inputFileName);
}
13.读取多个CSV文件
$inputFileNames = array('./sampleData/example1.csv','./sampleData/example2.csv');   // CSV文件数组
$objReader = PHPExcel_IOFactory::createReader($inputFileType);


/*
说明下面是干啥的：
1.先载入第一个CSV作为第一个工作表 | 设置工作表的标题
2.依次将多个CSV再入到 objPHPExcel 对象中，依次追加到第N个工作表 | 设置工作表的标题
3.获取Excel此时所有的标题，通过标题来依次获取工作表，然后对工作表进行操作！
*/
$inputFileName = array_shift($inputFileNames);      // 第一个CSV文件
$objPHPExcel = $objReader->load($inputFileName); // 读取第一个CSV文件
$objPHPExcel->getActiveSheet()->setTitle(pathinfo($inputFileName,PATHINFO_BASENAME)); // 设置标题
foreach($inputFileNames as $sheet => $inputFileName) {
$objReader->setSheetIndex($sheet+1); // 将工作表切换到下个工作表
$objReader->loadIntoExisting($inputFileName,$objPHPExcel);       // 将下一个CSV文件，载入到已存在的PHPExcel对象中
$objPHPExcel->getActiveSheet()->setTitle(pathinfo($inputFileName,PATHINFO_BASENAME));     // 设置当前工作表的标题
}


// 循环所有的工作表名称
$loadedSheetNames = $objPHPExcel->getSheetNames();
foreach($loadedSheetNames as $sheetIndex => $loadedSheetName) {
$objPHPExcel->setActiveSheetIndexByName($loadedSheetName);       // 通过 '工作表名称' 来设置当前工作表为激活状态
// 接着对当前激活的工作表，进行读取、数据库写入
}
14.将一个大的CSV文件，按 '块' 分成多个工作表(结合了12&13的示例)
class chunkReadFilter implements PHPExcel_Reader_IReadFilter
{
private $_startRow = 0;     // 开始行
private $_endRow = 0;       // 结束行


// 定义了一个读去指定范围行的方法
public function setRows($startRow, $chunkSize) {
$this->_startRow = $startRow;
$this->_endRow       = $startRow + $chunkSize;
}
public function readCell($column, $row, $worksheetName = '') {
if (($row == 1) || ($row >= $this->_startRow && $row < $this->_endRow)) {
return true;
}
return false;
}
}
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$chunkSize = 100;   // 定义每块读去的行数


// 在循环外部，实例化过滤器类，而不用循环内每次实例化(应该更优化)
$chunkFilter = new chunkReadFilter();
$objReader->setReadFilter($chunkFilter)
->setContiguous(true);     // 这里出现了一个没见过的方法(先放着，忘记是干啥的了)


$objPHPExcel = new PHPExcel();
$sheet = 0;     // 第一个工作表下标
for ($startRow = 2; $startRow <= 240; $startRow += $chunkSize) {
$chunkFilter->setRows($startRow,$chunkSize);
$objReader->setSheetIndex($sheet);       // 切换工作表
$objReader->loadIntoExisting($inputFileName,$objPHPExcel);       // 将读取到的CSV块，载入到工作表
$objPHPExcel->getActiveSheet()->setTitle('Country Data #'.(++$sheet));        // 设置工作表标题
}


// 循环所有的工作表名称
$loadedSheetNames = $objPHPExcel->getSheetNames();
foreach($loadedSheetNames as $sheetIndex => $loadedSheetName) {
$objPHPExcel->setActiveSheetIndexByName($loadedSheetName);       // 通过 '工作表名称' 来设置当前工作表为激活状态
// 接着对当前激活的工作表，进行读取、数据库写入
}


15.使用 'Advanced Value Binder' 读取通过 'tab' 分隔值的文件
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );       // 设置单元格
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objReader->setDelimiter("\t");      // 设置分隔符为 '\t'(tab分隔)
$objPHPExcel = $objReader->load($inputFileName);
$objPHPExcel->getActiveSheet()->setTitle(pathinfo($inputFileName,PATHINFO_BASENAME)); // 设置标题
$loadedSheetNames = $objPHPExcel->getSheetNames();       // 获取所有工作表名称


1)格式化输出
foreach($loadedSheetNames as $sheetIndex => $loadedSheetName) {
$objPHPExcel->setActiveSheetIndexByName($loadedSheetName);
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);    // 注意4个参数的区别
}
2)未格式化输出
foreach($loadedSheetNames as $sheetIndex => $loadedSheetName) {
$objPHPExcel->setActiveSheetIndexByName($loadedSheetName);
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,false,true);   // 注意4个参数的区别
}
3)单元格原生值
foreach($loadedSheetNames as $sheetIndex => $loadedSheetName) {
$objPHPExcel->setActiveSheetIndexByName($loadedSheetName);
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,false,true);  // 注意4个参数的区别
}
16.使用 'try/catch' 控制Excel加载时的异常
try {
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
} catch(PHPExcel_Reader_Exception $e) {
die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}
17.获取Excel的工作表名称列表
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$worksheetNames = $objReader->listWorksheetNames($inputFileName);        // 列出工作表名称
foreach($worksheetNames as $worksheetName) {
echo $worksheetName,'<br />';
}
18.不加载整个文件，或者Excel的工作表名称列表
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$worksheetData = $objReader->listWorksheetInfo($inputFileName);          // 列出工作表列表
foreach ($worksheetData as $worksheet) {
echo '<li>', $worksheet['worksheetName'], '<br />';
    echo 'Rows: ', $worksheet['totalRows'], ' Columns: ', $worksheet['totalColumns'], '<br />';
    echo 'Cell Range: A1:', $worksheet['lastColumnLetter'], $worksheet['totalRows'];
    echo '</li>';
}
19.全程，有一个方法：
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,false,true);
getActiveSheet() - 获取当前激活的工作表
toArray() - 将当前激活的工作表，解析全部放入数组中