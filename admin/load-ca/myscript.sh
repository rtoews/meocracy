y=1
while [ $y -le 12 ]; do
    x=1
    while [ $x -le 12 ]; do
        printf "% 4d" $(( $x * $y ))
        let x++
    done
    echo ""
    let y++
done
