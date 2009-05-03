@echo off
:: CLEAR CACHE
del /Q .\tmp\cache\models\*.*
del /Q .\tmp\cache\persistent\*.*
del /Q .\tmp\cache\views\*.*
del /Q .\tmp\cache\*.*

del /Q .\tmp\logs\*.*
pause