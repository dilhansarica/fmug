@echo off

.\bin\jsmin < %1 > %1.tmp
del /q %1
move %1.tmp %1
