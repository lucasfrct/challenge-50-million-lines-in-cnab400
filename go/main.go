package main

import (
	"bufio"
	"fmt"
	"os"
	"sync"
	"time"
)

func main() {

	var file string = "./temp/cnab"
	cnab, _ := os.Create(file)
	_ = cnab.Close()

	start := time.Now()

	var wg sync.WaitGroup

	var threads int = 50000
	var limitLines int = 50000000 // 50 milhoes de linhas
	var linesPerWrited int = (limitLines / threads)
	var charsPerLine int = 400

	line := []byte{}
	for k := 1; k <= charsPerLine; k++ {
		line = append(line, []byte("a")...)
	}
	line = append(line, []byte("\n")...)

	content := []byte{}
	for j := 1; j <= linesPerWrited; j++ {
		if j > limitLines {
			break
		}
		content = append(content, line...)
	}

	cnab, _ = os.OpenFile(file, os.O_WRONLY|os.O_APPEND|os.O_CREATE, 0644)

	rowLines := 0
	total := 0

	for true {
		rowLines += linesPerWrited
		if rowLines > limitLines {
			break
		}

		wg.Add(1)

		go func(position int) {
			defer wg.Done()
			cnab.Seek(int64(position), 0)
			bufferedWriterNext := bufio.NewWriter(cnab)
			_, _ = bufferedWriterNext.Write(content)
			bufferedWriterNext.Flush()

			total += linesPerWrited
			duration := time.Since(start)
			fmt.Printf("%vs - Thread %v | Linha : %vM position: %v\n", int(duration.Seconds()), (position / linesPerWrited), (total / 1000000), position)
		}(rowLines)

	}

	wg.Wait()
	cnab.Close()

	elapsed := time.Since(start)

	fmt.Printf("Caracteres por linha: %v \n", len(line))
	fmt.Printf("Caracteres inseridos por vez: %v \n", len(content))
	fmt.Printf("Linhas escritas por vez: %v \n", linesPerWrited)
	fmt.Printf("Limite de linhas: %v \n", limitLines)

	min := int64(elapsed.Minutes())
	sec := elapsed.Seconds()
	ms := elapsed.Milliseconds() - (int64(sec) * 1000)
	mcs := elapsed.Microseconds() - (elapsed.Milliseconds() * 1000)
	ns := elapsed.Nanoseconds() - (elapsed.Microseconds() * 1000)

	fmt.Printf("Tempo de execução: %vm %vs %vms %vmcs %vns \n", min, int64(sec), ms, mcs, ns)
}
