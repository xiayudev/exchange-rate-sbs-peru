<?php
# java -Dwebdriver.chrome.driver="C:\ProgramData\chocolatey\bin\chromedriver.exe" -jar selenium-server-standalone-4.0.0-alpha-1.jar
# java -Dwebdriver.chrome.driver="C:\ProgramData\chocolatey\bin\chromedriver.exe" -jar selenium-server-4.15.0.jar standalone
require_once ('vendor/autoload.php');

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

// Set up Selenium server connection
$host = 'http://localhost:4444';
$capabilities = DesiredCapabilities::chrome();
$driver = RemoteWebDriver::create($host, $capabilities);

// Navigate to a website
$driver->get("https://www.sbs.gob.pe/");
$driver->manage()->window()->maximize();

try {
    $driver->findElement (WebDriverBy::cssSelector(".d-none > span"))->click();
    $driver->findElement (WebDriverBy::linkText("Aplicativo series estadísticas"))->click();

    $driver->findElement (WebDriverBy::id("ctl00_ContentPlaceHolder1_ListView1_ctl02_rb_elemento"))->click();
    $driver->findElement (WebDriverBy::id("ctl00_ContentPlaceHolder1_btnContinuarInf"))->click();
    $driver->switchTo()->frame(0);
    // Wait for the element to be present
    $element = $driver->wait(20, 1000)->until(
    # WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('FCH_FECHA_BOTON_1'))
        WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('FECHA_CONSULTA_1'))
    );
    #$element->click ();
    #$driver->findElement (WebDriverBy::cssSelector(".calendar .headrow td:nth-child(3) div"))->click ();
    #$driver->findElement (WebDriverBy::cssSelector(".calendar thead tr>td:nth-child(3) div"))->click ();
    $element->sendKeys("27/11/2023");
    $driver->findElement(WebDriverBy::name("s_moneda"))->click();
    $select = $driver->findElement (WebDriverBy::name("s_moneda"));
    $select->findElement(WebDriverBy::xpath("//option[. = 'Dólar de N.A.']"));
    $driver->findElement(WebDriverBy::id("button222"))->click();

    // Get and print the exchange rate in Dollar N.A -> This is automatically generated
    //TODO: fix this!
    $title = $driver->wait(20, 1000)->until(
        WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector("APLI_fila2"))
    );
    $title->getText();
    echo "Title: " . $title;
} catch (Exception $e) {
    echo "Error: " . $e;
} finally {
    // Close the browser
    $driver->quit();
}
