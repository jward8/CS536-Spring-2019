<html>
<head>
<link rel="stylesheet" href="dextras.css" />

<script type="text/x-mathjax-config">
    // <![CDATA[
    MathJax.Hub.Config({
        TeX: {extensions: ["AMSmath.js", "AMSsymbols.js"]},
        extensions: ["tex2jax.js"],
        jax: ["input/TeX", "output/HTML-CSS"],
        showProcessingMessages : false,
        messageStyle : "none" ,
        showMathMenu: false ,
        tex2jax: {
            processEnvironments: true,
            inlineMath: [ ['$','$'] ],
            displayMath: [ ['$$','$$'] ],
            preview : "none",
            processEscapes: true
        },
        "HTML-CSS": { linebreaks: { automatic:true, width: "latex-container"} }
    });
    // ]]>
 // MathJax.Hub.Queue(reportReload);
</script>
<script type="text/javascript" src="MathJax.js"></script>
<script type="text/javascript" src="latex2html5.min.js"></script>


</head>
<body>
<A NAME=top></a>

<HR>
<H2>Contents</H2>

<UL>
  <LI><A HREF="#overview">Overview</A>
  <LI><A HREF="#value">Value Parameters</A>
    <UL>
      <LI><A HREF="#youtry1">Test Yourself #1</A>
    </UL>
  <LI><A HREF="#ref">Reference Parameters</A>
    <UL>
      <LI><A HREF="#youtry2">Test Yourself #2</A>
      <LI><A HREF="#youtry3">Test Yourself #3</A>
    </UL>
  <LI><A HREF="#valueResult">Value-Result Parameters</A>
    <UL>
      <LI><A HREF="#alias1">Creating Aliases via Pointers</A>
      <LI><A HREF="#alias2">Creating Aliases by Passing Globals as Arguments</A>
      <LI><A HREF="#alias3">Creating Aliases by Passing Same Argument Twice</A>
      <LI><A HREF="#youtry4">Test Yourself #4</A>
    </UL>
  <LI><A HREF="#name">Name Parameters</A>
  <LI><A HREF="#summ">Comparison</A>
</UL>

<P>
<a name=overview></a>
<span class=subhead>Overview</span>
<P>
In a Java program, all parameters are passed by <em>value</em>.
However, there are three other parameter-passing modes that have been
used in programming languages:
<OL class=style1>
  <LI> pass by reference
  <LI> pass by value-result (also called copy-restore)
  <LI> pass by name
</OL>
We will consider each of those modes, both from the point of view of the
programmer and from the point of view of the compiler writer.
<P>
First, here's some useful terminology:
<OL class=style1>
  <LI> Given a method header, e.g.:
  <pre>
       void f(int a, boolean b, int c)
  </pre>
       we will use the terms <em><b>parameters, formal parameters</b></em>,
       or just <b><em>formals</em></b> to refer to a, b, and c.
  <P>
  <LI> Given a method call, e.g.:
  <pre>
       f(x, x==y, 6);
  </pre>
      we will use the terms <em><b>arguments, actual parameters</b></em>,
      or just <em><b>actuals</b></em> to refer to x, x==y, and 6.
  <P>
  <LI> The term <em><b>r-value</b></em> refers to the value of an expression.
       So for example, assuming that variable x has been initialized to 2,
       and variable y has been initialized to 3:
<div align=center>
       <TABLE align=center style="width:200px" class=cfg>
			 <tr>
					<td style="border-bottom:1px solid"><b>expression</b></td>
					<td style="padding-left:20px"></td>
          <td style="border-bottom:1px solid"><b>r-value</b></td>
				</tr>
			 <tr>
					<td>x</td>
					<td></td>
					<td>2</td>
			 </tr>
			 <tr>
					<td>y</td>
					<td></td>
					<td>3</td>
			 </tr>
			 <tr>
					<td>x+y</td>
					<td></td>
					<td>5
			 </tr>
			 <tr>
					<td>x==y</td>
					<td></td>
					<td>false</td>
			 </tr>
       </TABLE>
</div>
  <P>
  <LI> The term <em><b>l-value</b></em> refers to the location or address of
       an expression.  For example, the l-value of a global variable is the
       location in the static data area where it is stored.  The l-value of
       a local variable is the location on the stack where it is (currently)
       stored.  Expressions like <tt>x+y</tt> and <tt>x==y</tt> have no
       l-value.  However, it is not true that only identifiers have l-values;
       for example, if A is an array, the expression <tt>A[x+y]</tt>
       has both an r-value (the value stored in the x+y<sup>th</sup>
       element of the array), and an l-value (the address of that element).
</OL>
L-values and r-values get their names from the Left and Right sides of an
assignment statement.
For example, the code generated for the statement <tt>x = y</tt> uses the
l-value of x (the left-hand side of the assignment) and the r-value of y
(the right-hand side of the assignment).
Every expression has an r-value.
An expression has an l-value iff it can be used on the left-hand side
of an assignment.

<P>
<a name=value></a>
<span class=subhead>Value Parameters</span>
<P>
Parameters can only be passed by value in Java and in C.
In Pascal, a parameter is passed by value unless the corresponding
formal has the keyword <b><em>var</em></b>;
similarly, in C++, a parameter is passed by value unless the corresponding
formal has the symbol <b><em>&</em></b> in front of its name.
For example, in the Pascal and C++ code below,
parameter x is passed by value, but not parameter y:
<UL>
<pre>
// Pascal procedure header
Procedure f(x: integer; <em><b>var</b></em> y: integer);

// C++ function header
void f(int x; int <b><em>&</em></b> y);
</pre>
</UL>
When a parameter is passed by value, the calling method copies the r-value
of the argument into the called method's AR.
Since the called method only has access to
the copy, changing a formal parameter (in the called method) has
<b><em>no</em></b> effect on the corresponding argument.
Of course, if the argument is a pointer, then changing the
"thing pointed to" <em><b>does</b></em> have an effect that can be
"seen" in the calling procedure.
For example, in Java, arrays are really pointers, so if an array is
passed as an argument to a method, the called method
can change the <em><b>contents</b></em> of the array,
but not the array variable itself, as illustrated below:
<UL>
<pre>
void f( int[] A ) {
    A[0] = 10;   // change an element of parameter A
    A = null;    // change A itself (but not the corresponding actual)
}

void g() {
    int[] B = new int [3];
    B[0] = 5;
    f(B);
    //*** B is <em>not</em> null here, because B was passed by value
    //*** however, B[0] is now 10, because method f changed the first element
    //*** of the array pointed to by B
}
</pre>
</UL>
<P>
<hr>
<a name=youtry1>
<P>
<center>
<u><b>TEST YOURSELF #1</b></u>
</center></a>
<P>
What is printed when the following Java program executes and why?
<UL>
<pre>
class Person {
    int age;
    String name;
}

class Test {
    static void changePerson(Person P) {
        P.age = 10;
	P = new Person();
	P.name = "Joe";
    }

    public static void main(String[] args) {
        Person P = new Person();
	P.age = 2;
	P.name = "Ann";
	changePerson(P);
	System.out.println(P.age);
	System.out.println(P.name);
    }
}
</pre>
</UL>
<p><a href="SOLUTIONS/PARAMETERS-ANSWERS.html#ans1">solution</a></p>
<hr>

<P>
<a name=ref></a>
<span class=subhead>Reference Parameters</span>
<P>
When a parameter is passed by reference, the calling method copies the
l-value of the argument into the called method's AR (i.e., it copies
a <b><em>pointer</em></b> to the argument instead of copying the
argument's value).
Each time the formal is used, the pointer is followed.
If the formal is used as an r-value (e.g., its value is printed,
or assigned to another variable), the value is fetched from the
location pointed to by the pointer.
If the formal is assigned a new value, that new value is written
into the location pointed to by the pointer (the new value is
<em>not</em> written into the called method's AR).
<P>
If an argument passed by reference has no l-value (e.g., it is an
expression like <tt>x+y</tt>), the compiler may consider this an error
(that is what happens in Pascal, and is also done by some C++
compilers),
or it may give a warning, then generate code to evaluate the expression,
to store the result in some temporary location, and to copy the address
of that location into the called method's AR (this is is done by some C++
compilers).
<P>
In terms of language design, it seems like a good idea to consider this
kind of situation an error.
Here's an example of code in which an expression with no l-value is
used as an argument that is passed by reference (the example was actually
a Fortran program, but Java-like syntax is used here):

<pre>
        void mistake(int x) {  // x is a reference parameter
	   x = x+1;
	}

        void main() {
           int a;
	   mistake(1);
	   a = 1;
           print(a);
        }
</pre>
When this program was compiled and executed, the output was 2!  That
was because the Fortran compiler stored 1 as a literal at some address
and used that address for all the literal "1"s in the program.  In
particular, that address was passed when "mistake" was called, and was
also used to fetch the value to be assigned into variable a.  When
"mistake" incremented its parameter, the location that held the value
1 was incremented; therefore, when the assignment to a was executed,
the location no longer held a 1, and so a was initialized to 2!
<P>
To understand why reference parameters are useful, remember that, although
in Java all non-primitive types are really pointers,
that is not true in other languages.
For example, consider the following C++ code:
<UL>
<pre>
class Person {
  public:
     String name;
     int age;
};

void birthday(Person per) {
   per.age++;
}

void main() {
   Person P;
   P.age = 0;
   birthday(P);
   print(P.age);
}
</pre>
</UL>
Note that in <tt>main</tt>, variable <tt>P</tt> is a <tt>Person</tt>, not
a pointer to a <tt>Person</tt>;
i.e., <tt>main</tt>'s activation record has space for <tt>P.name</tt> and
<tt>P.age</tt>.
Parameter <tt>per</tt> is passed by value (there is no ampersand), so
when <tt>birthday</tt> is called from <tt>main</tt>, a copy of variable
<tt>P</tt> is made (i.e., the values of its <tt>name</tt> and <tt>age</tt>
fields are copied into <tt>birthday</tt>'s AR).
It is only the copy of the <tt>age</tt> field that is updated by
<tt>birthday</tt>, so when the print statement in <tt>main</tt> is
executed, the value that is output is 0.
<P>
This motivates some reasons for using reference parameters:
<OL>
  <LI> When the job of the called method is to modify the parameter
       (e.g., to update the fields of a class), the
       parameter must be passed by reference so that the actual parameter,
       not just a copy, is updated.
  <LI> When the called method will <em><b>not</b></em> modify the parameter,
       and the parameter is very large, it would be
       time-consuming to copy the parameter; it is better to pass the
       parameter by reference so that a single pointer can be passed.
</OL>
<P>
<hr>
<a name=youtry2>
<P>
<center>
<u><b>TEST YOURSELF #2</b></u>
</center></a>
<P>
Consider writing a method to sort the values in an array of integers.
An operation that is used by many sorting algorithms is to swap the
values in two array elements.
This might be accomplished using a <tt>swap</tt> method:
<UL>
<pre>
static void swap(int x, int y) {
    int tmp = x;
    x = y;
    y = tmp;
}
</pre>
</UL>
Assume that <tt>A</tt> is an array of 4 integers.
Draw two pictures to illustrate what happens when the call:
<UL>
<pre>
swap(A[0], A[1]);
</pre>
</UL>
is executed, first assuming that this is Java code (all parameters are
passed by value), and then assuming that this is some other language
in which parameters are passed by reference.
In both cases, assume that the array itself is stored in the
heap (i.e., the space for <tt>A</tt> in the calling method's AR
holds a pointer to the space allocated for the array in the heap).
Your pictures should show the ARs of the calling method and
method <tt>swap</tt>.
</OL>
<p><a href="SOLUTIONS/PARAMETERS-ANSWERS.html#ans2">solution</a></p>
<hr>
<P>
It is important to realize that the code generator will generate
different code for a use of a parameter in a method, depending on
whether it is passed by value or by reference.
If it is passed by value, then it is in the
called method's AR (accessed using an offset from the FP).
However, if it is passed by reference, then it is in some other storage
(another method's AR, or in the static data area).  The value in the called
method's AR is the address of that other location.
<P>
To make this more concrete, assume the following code:
<UL>
<pre>
void f(int a) {
   a = a - 5;
}

void main() {
    int x = 10;
    f(a);
}
</pre>
</UL>
Below is the code that would be generated for the statement
<tt>a = a - 5</tt>,
assuming (1) that <tt>a</tt> is passed by value and (2) assuming that
<tt>a</tt> is passed by reference:
<div class=row>
<div class=6u style="border:1px solid">
	<center>
	<p>Passed by Value</p>
	<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="190"
   height="130"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_passbyvalue.svg">
  <defs
     id="defs4" />
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="2.2578126"
     inkscape:cx="127.40926"
     inkscape:cy="43.241648"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title />
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-121.07976,-362.95536)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
       x="143.7301"
       y="429.02753"
       id="text2985"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2987"
         x="143.7301"
         y="429.02753">lw t0,(FP)</tspan><tspan
         sodipodi:role="line"
         x="143.7301"
         y="446.52753"
         id="tspan2989">sub t0,t0,5</tspan><tspan
         sodipodi:role="line"
         x="143.7301"
         y="464.02753"
         id="tspan2991">sw t0,(FP)</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 261,439.36218 C 255.2976,434.47115 246.63642,441.40197 241.9375,440.11218"
       id="path2993"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <text
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa"
       x="262.56885"
       y="445.78589"
       id="text2995"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2997"
         x="262.56885"
         y="445.78589">t0=t0-5</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 244.0073,438.53804 241.00209,440.12903 243.83052,441.8968"
       id="path2999"
       inkscape:connector-curvature="0" />
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 217.31402,412.90542 C 217.31402,412.90542 233.60324,416.2678 232.34003,421.03714 231.07682,425.80648 227.03673,424.57268 227.03673,424.57268"
       id="path3001"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="czc" />
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 227.56706,422.62814 226.32963,424.3959 228.09739,426.34045"
       id="path3003"
       inkscape:connector-curvature="0" />
    <text
       sodipodi:linespacing="125%"
       id="text3013"
       y="407.28336"
       x="122.26376"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa"
       xml:space="preserve"><tspan
         y="407.28336"
         x="122.26376"
         id="tspan3015"
         sodipodi:role="line">load a's r-value into t<tspan
   style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
   id="tspan3017">0</tspan></tspan></text>
    <text
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa"
       x="122.26376"
       y="407.28336"
       id="text3019"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3021"
         x="122.26376"
         y="407.28336">load a's r-value into t<tspan
   id="tspan3023"
   style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">0</tspan></tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3025"
       y="490.28336"
       x="122.26376"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa"
       xml:space="preserve"><tspan
         y="490.28336"
         x="122.26376"
         id="tspan3027"
         sodipodi:role="line">store result into f's AR</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 216.60691,476.89858 C 216.60691,476.89858 240.91273,474.8852 241.92367,468.93107 242.93461,462.97694 227.21351,462.04934 227.21351,462.04934"
       id="path3031"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="czc" />
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 229.75,465.36218 226.25,462.36218 231,459.61218"
       id="path3033"
       inkscape:connector-curvature="0" />
  </g>
</svg>
	</center>
</div>
<div class=6u style="border:1px solid">
	<center>
	<p>Passed by Reference</p>
	<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="210"
   height="130"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_passbyref.svg">
  <defs
     id="defs4" />
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="1.5965145"
     inkscape:cx="-16.135904"
     inkscape:cy="69.64834"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="1200"
     inkscape:window-height="724"
     inkscape:window-x="0"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title />
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-103.02916,-376.53027)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
       x="145.7301"
       y="427.02753"
       id="text2985"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2987"
         x="145.7301"
         y="427.02753">lw t0,(FP)</tspan><tspan
         sodipodi:role="line"
         x="145.7301"
         y="444.52753"
         id="tspan2989">lw t1,(t0)</tspan><tspan
         sodipodi:role="line"
         x="145.7301"
         y="462.02753"
         id="tspan3054">sub t1,t1,5</tspan><tspan
         sodipodi:role="line"
         x="145.7301"
         y="479.52753"
         id="tspan2991">sw t1,(t0)</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 261.13225,456.30226 C 255.42985,451.41123 246.76867,458.34205 242.06975,457.05226"
       id="path2993"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <text
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa"
       x="262.70111"
       y="462.72598"
       id="text2995"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2997"
         x="262.70111"
         y="462.72598"><tspan
   style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
   id="tspan3066">t1</tspan>=<tspan
   style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
   id="tspan3068">t1</tspan>-5</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 244.13955,455.47812 241.13434,457.06911 243.96277,458.83688"
       id="path2999"
       inkscape:connector-curvature="0" />
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 247.17837,419.73218 C 226.20903,419.12013 251.01834,438.96279 232.86419,439.89048"
       id="path3001"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 234.49803,438.00181 233.13605,439.6755 234.75861,441.74276"
       id="path3003"
       inkscape:connector-curvature="0" />
    <text
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa"
       x="154.33"
       y="390.57266"
       id="text3019"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3021"
         x="158.20891"
         y="390.57266"
         style="text-align:center;text-anchor:middle">load a's </tspan><tspan
         sodipodi:role="line"
         x="154.33"
         y="407.96667"
         id="tspan3098"
         style="text-align:center;text-anchor:middle">L-value into t<tspan
   id="tspan3023"
   style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;text-anchor:middle;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter">0</tspan></tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3025"
       y="505.64227"
       x="127.05414"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa"
       xml:space="preserve"><tspan
         y="505.64227"
         x="127.05414"
         id="tspan3027"
         sodipodi:role="line">store result into main's AR</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 221.39728,492.25749 C 221.39728,492.25749 245.7031,490.24411 246.71404,484.28998 247.72498,478.33585 232.00388,477.40825 232.00388,477.40825"
       id="path3031"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="czc" />
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 234.54037,480.72109 231.04037,477.72109 235.79037,474.97109"
       id="path3033"
       inkscape:connector-curvature="0" />
    <text
       sodipodi:linespacing="125%"
       id="text3056"
       y="407.71228"
       x="255.33052"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#800080;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa"
       xml:space="preserve"><tspan
         y="407.71228"
         x="255.33052"
         id="tspan3058"
         sodipodi:role="line">load a's </tspan><tspan
         y="422.71228"
         x="255.33052"
         sodipodi:role="line"
         id="tspan3092">r-value </tspan><tspan
         y="440.10629"
         x="255.33052"
         sodipodi:role="line"
         id="tspan3095">into t<tspan
   style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
   id="tspan3060">1</tspan></tspan></text>
    <path
       sodipodi:nodetypes="cc"
       inkscape:connector-curvature="0"
       id="path3062"
       d="M 141.74889,410.99336 C 145.77684,418.5652 126.45042,416.4969 144.19127,422.97344"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
    <path
       inkscape:connector-curvature="0"
       id="path3064"
       d="M 143.13294,424.71492 144.60488,423.13707 143.12567,420.96493"
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
  </g>
</svg>
	</center>
</div>
</div>
 <P>
 Notice that when passing by reference the cell at address FP contains the <i>address</i> of <tt>a</tt>
 and not its value. Therefore, the first instruction will copy the location of <tt>a</tt> into t0 and the second instruction
 will extract the value at such a location and copy it into t1. Finally, the last instruction will copy the result into the location pointed by t0-i.e., the location of <tt>a</tt>.
 </P>
<UL>
</UL>
<P>
<hr>
<a name=youtry3>
<P>
<center>
<u><b>TEST YOURSELF #3</b></u>
</center></a>
<P>
The code generator will also generate different code for a method
<em><b>call</b></em> depending on whether the arguments are to be passed
by value or by reference.
Consider the following code:
<UL>
<pre>
    int x, y;
    x = y = 3;
    f(x, y);
</pre>
</UL>
Assume that <tt>f</tt>'s first parameter is passed by reference, and that its
second parameter is passed by value.
What code would be generated to fill in the parameter fields of <tt>f</tt>'s
AR?
 <p><a href="SOLUTIONS/PARAMETERS-ANSWERS.html#ans3">solution</a></p>
 <hr>

<P>
<a name=valueResult></a>
<span class=subhead>Value-Result Parameters</span>
<P>
Value-result parameter passing was used in Fortran IV and in Ada.
The idea is that, as for pass-by-value, the value (not the address) of
the actual parameters are copied into the called method's AR.
However, when the called method ends, the final values of the parameters
are copied back into the arguments.
Value-result is equivalent to call-by-reference <em><b>except</b></em>
when there is aliasing (note: "equivalent" means the program will produce
the same results, not that the same code will be generated).
<P>
Two expressions that have the same l-value are called aliases.
Aliasing can happen:
<UL>
  <LI> via pointer manipulation,
  <LI> when a parameter is passed by reference and is also global to the
       called method,
  <LI> when a parameter is passed by reference using the same expression
       as an argument more than once; e.g., call test(x,y,x).
</UL>
Will will look at examples of each of these below.
<a name="alias1"></a>
<p>
<H3>Creating Aliases via Pointers</H3>
<P>
Pointer manipulation can create aliases,
as illustrated by the following Java code.
(Note: this kind of aliasing does not make pass-by-reference different
from pass-by-value-result; it is included here only for completeness of
the discussion of aliasing.)
<UL>
<pre>
Person p, q;
  p = new Person();
  q = p;
  // now p.name and q.name are aliases (they both refer to the same location)
  // however, p and q are <em>not</em> aliases (they refer to different locations)
</pre>
<p>Pictorially:</p>
<div class=row>
	<div class=12u>
		<center>
		<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="198"
   height="160"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_1.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="1.1289062"
     inkscape:cx="189.19241"
     inkscape:cy="61.677632"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title />
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-244.914,-336.78618)">
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect2985"
       width="113.89026"
       height="103.13742"
       x="318.36185"
       y="387.26776"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="326.20612"
       y="420.30682"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="326.20612"
         y="420.30682">name:</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text2991"
       y="464.30682"
       x="326.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="464.30682"
         x="326.20612"
         id="tspan2993"
         sodipodi:role="line">age:</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 318.61612,439.18657 432.53125,439.18657"
       id="path2995"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="315"
       y="371.24973"
       id="text2997"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2999"
         x="315"
         y="371.24973">A Person Object</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="246.5"
       y="445.43658"
       id="text3001"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3003"
         x="246.5"
         y="445.43658">p</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text3005"
       y="399.43658"
       x="247"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="399.43658"
         x="247"
         id="tspan3007"
         sodipodi:role="line">q</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect3009"
       width="29"
       height="29"
       x="258.5"
       y="428.43658" />
    <rect
       y="381.93658"
       x="259"
       height="29"
       width="29"
       id="rect3011"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Lend)"
       d="M 274,397.43657 C 280.6977,398.49588 263.03062,332.93974 318.5,386.43657"
       id="path3013"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Lend)"
       d="M 269.5,443.93657 C 307.71812,441.03314 299.45012,412.76001 317.5,387.93657"
       id="path3015"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
  </g>
</svg>
		</center>
	</div>
</div>
</UL>

<a name="alias2"></a>
<P>
<H3>Creating Aliases by Passing Globals as Arguments</H3>
<P>
This way of creating aliases (and the difference between reference parameters
and value-result parameters in the presence of this kind of aliasing)
are illustrated by the following C++ code:
<UL>
<pre>
int x = 1;      // a global variable

void f(int & a)
{ a = 2;        // when f is called from main, a and x are aliases
  x = 0;
}

main()
{ f(x);
  cout << x;
}
</pre>
</UL>
As stated above, passing parameters by value-result yields the same results
as passing parameters by reference <em><b>except</b></em> when there is
aliasing.
The above code will print different values when <tt>f</tt>'s parameter is
passed by reference than when it is passed by value-result.
To understand why, look at the following pictures, which show the effect
of the code on the activation records (only variables and parameters are
shown in the ARs, and we assume that variable <tt>x</tt> is in the
static data area):
<table>
	<tr>
		<td width=50%><center><p><b>Call-by-reference</b></p></center></td>
		<td width=50%><center><p><b>Call-by-value</b></p></center></td>
	</tr>

	<tr><td colspan=2><center><p><u>At time of call</u></p></center></td></tr>
	<tr>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="189"
   height="112"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_ref2.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="3.1930291"
     inkscape:cx="102.3968"
     inkscape:cy="55.924125"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-229.8895,-314.94547)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="336.43253"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="328.20612"
         y="336.43253">x:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4442"
       width="51.160553"
       height="28.891205"
       x="320.36185"
       y="317.48697"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="387.52158"
       id="text4450"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4452"
         x="328.20612"
         y="387.52158">a:</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4454"
       width="50.160553"
       height="28.891205"
       x="320.36185"
       y="367.69156"
       rx="0"
       ry="0" />
    <text
       sodipodi:linespacing="125%"
       id="text4460"
       y="389.0737"
       x="297.93231"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:14px"
         id="tspan4464"
         y="389.0737"
         x="297.93231"
         sodipodi:role="line">f 's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4468"
       d="M 307.86505,370.87686 C 307.86505,370.87686 305.76639,369.09185 303.98138,370.87686 301.18984,374.11036 307.76644,383.23676 301.94245,384.08135"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,397.28801 C 307.86505,397.28801 305.76639,399.07302 303.98138,397.28801 301.18984,394.05451 307.76644,384.92811 301.94245,384.08352"
       id="path4470"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="326.8483"
       id="text4476"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="326.8483"
         id="tspan4478"
         style="font-size:14px">Static Data</tspan><tspan
         sodipodi:role="line"
         x="297.93231"
         y="344.3483"
         style="font-size:14px"
         id="tspan4480">Area</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,317.4903 C 307.86505,317.4903 305.76639,315.70529 303.98138,317.4903 301.18984,320.7238 307.76644,329.8502 301.94245,330.69479"
       id="path4482"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4484"
       d="M 307.86505,343.90145 C 307.86505,343.90145 305.76639,345.68646 303.98138,343.90145 301.18984,340.66795 307.76644,331.54155 301.94245,330.69696"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Lend)"
       d="M 353.414,383.9075 C 421.14499,396.00608 429.78306,365.13182 408.914,339.9075 398.85366,328.9787 372.414,330.9075 372.414,330.9075"
       id="path4486"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <text
       sodipodi:linespacing="125%"
       id="text4670"
       y="336.43253"
       x="348.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="336.43253"
         x="348.20612"
         id="tspan4672"
         sodipodi:role="line">1</tspan></text>
  </g>
</svg>
</center></td>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="189"
   height="112"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_val1.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="3.1930291"
     inkscape:cx="102.3968"
     inkscape:cy="55.924125"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-229.8895,-314.94547)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="336.43253"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="328.20612"
         y="336.43253">x:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4442"
       width="51.160553"
       height="28.891205"
       x="320.36185"
       y="317.48697"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="387.52158"
       id="text4450"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4452"
         x="328.20612"
         y="387.52158">a:</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4454"
       width="50.160553"
       height="28.891205"
       x="320.36185"
       y="367.69156"
       rx="0"
       ry="0" />
    <text
       sodipodi:linespacing="125%"
       id="text4460"
       y="389.0737"
       x="297.93231"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:14px"
         id="tspan4464"
         y="389.0737"
         x="297.93231"
         sodipodi:role="line">f 's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4468"
       d="M 307.86505,370.87686 C 307.86505,370.87686 305.76639,369.09185 303.98138,370.87686 301.18984,374.11036 307.76644,383.23676 301.94245,384.08135"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,397.28801 C 307.86505,397.28801 305.76639,399.07302 303.98138,397.28801 301.18984,394.05451 307.76644,384.92811 301.94245,384.08352"
       id="path4470"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="326.8483"
       id="text4476"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="326.8483"
         id="tspan4478"
         style="font-size:14px">Static Data</tspan><tspan
         sodipodi:role="line"
         x="297.93231"
         y="344.3483"
         style="font-size:14px"
         id="tspan4480">Area</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,317.4903 C 307.86505,317.4903 305.76639,315.70529 303.98138,317.4903 301.18984,320.7238 307.76644,329.8502 301.94245,330.69479"
       id="path4482"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4484"
       d="M 307.86505,343.90145 C 307.86505,343.90145 305.76639,345.68646 303.98138,343.90145 301.18984,340.66795 307.76644,331.54155 301.94245,330.69696"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       sodipodi:linespacing="125%"
       id="text4670"
       y="336.43253"
       x="348.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="336.43253"
         x="348.20612"
         id="tspan4672"
         sodipodi:role="line">1</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="348.20612"
       y="388.42078"
       id="text4780"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4782"
         x="348.20612"
         y="388.42078">1</tspan></text>
  </g>
</svg>
</center></td>
	</tr>

	<tr><td colspan=2><center><p><u>After a = 2</u></p></center></td></tr>
	<tr>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="189"
   height="112"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_ref3.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="3.1930291"
     inkscape:cx="102.3968"
     inkscape:cy="55.924125"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-229.8895,-314.94547)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="336.43253"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="328.20612"
         y="336.43253">x:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4442"
       width="51.160553"
       height="28.891205"
       x="320.36185"
       y="317.48697"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="387.52158"
       id="text4450"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4452"
         x="328.20612"
         y="387.52158">a:</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4454"
       width="50.160553"
       height="28.891205"
       x="320.36185"
       y="367.69156"
       rx="0"
       ry="0" />
    <text
       sodipodi:linespacing="125%"
       id="text4460"
       y="389.0737"
       x="297.93231"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:14px"
         id="tspan4464"
         y="389.0737"
         x="297.93231"
         sodipodi:role="line">f 's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4468"
       d="M 307.86505,370.87686 C 307.86505,370.87686 305.76639,369.09185 303.98138,370.87686 301.18984,374.11036 307.76644,383.23676 301.94245,384.08135"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,397.28801 C 307.86505,397.28801 305.76639,399.07302 303.98138,397.28801 301.18984,394.05451 307.76644,384.92811 301.94245,384.08352"
       id="path4470"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="326.8483"
       id="text4476"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="326.8483"
         id="tspan4478"
         style="font-size:14px">Static Data</tspan><tspan
         sodipodi:role="line"
         x="297.93231"
         y="344.3483"
         style="font-size:14px"
         id="tspan4480">Area</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,317.4903 C 307.86505,317.4903 305.76639,315.70529 303.98138,317.4903 301.18984,320.7238 307.76644,329.8502 301.94245,330.69479"
       id="path4482"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4484"
       d="M 307.86505,343.90145 C 307.86505,343.90145 305.76639,345.68646 303.98138,343.90145 301.18984,340.66795 307.76644,331.54155 301.94245,330.69696"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Lend)"
       d="M 353.414,383.9075 C 421.14499,396.00608 429.78306,365.13182 408.914,339.9075 398.85366,328.9787 372.414,330.9075 372.414,330.9075"
       id="path4486"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <text
       sodipodi:linespacing="125%"
       id="text4670"
       y="336.43253"
       x="348.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="336.43253"
         x="348.20612"
         id="tspan4672"
         sodipodi:role="line">2</tspan></text>
  </g>
</svg>
</center></td>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="189"
   height="112"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_val1.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="3.1930291"
     inkscape:cx="102.3968"
     inkscape:cy="55.924125"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-229.8895,-314.94547)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="336.43253"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="328.20612"
         y="336.43253">x:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4442"
       width="51.160553"
       height="28.891205"
       x="320.36185"
       y="317.48697"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="387.52158"
       id="text4450"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4452"
         x="328.20612"
         y="387.52158">a:</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4454"
       width="50.160553"
       height="28.891205"
       x="320.36185"
       y="367.69156"
       rx="0"
       ry="0" />
    <text
       sodipodi:linespacing="125%"
       id="text4460"
       y="389.0737"
       x="297.93231"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:14px"
         id="tspan4464"
         y="389.0737"
         x="297.93231"
         sodipodi:role="line">f 's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4468"
       d="M 307.86505,370.87686 C 307.86505,370.87686 305.76639,369.09185 303.98138,370.87686 301.18984,374.11036 307.76644,383.23676 301.94245,384.08135"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,397.28801 C 307.86505,397.28801 305.76639,399.07302 303.98138,397.28801 301.18984,394.05451 307.76644,384.92811 301.94245,384.08352"
       id="path4470"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="326.8483"
       id="text4476"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="326.8483"
         id="tspan4478"
         style="font-size:14px">Static Data</tspan><tspan
         sodipodi:role="line"
         x="297.93231"
         y="344.3483"
         style="font-size:14px"
         id="tspan4480">Area</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,317.4903 C 307.86505,317.4903 305.76639,315.70529 303.98138,317.4903 301.18984,320.7238 307.76644,329.8502 301.94245,330.69479"
       id="path4482"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4484"
       d="M 307.86505,343.90145 C 307.86505,343.90145 305.76639,345.68646 303.98138,343.90145 301.18984,340.66795 307.76644,331.54155 301.94245,330.69696"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       sodipodi:linespacing="125%"
       id="text4670"
       y="336.43253"
       x="348.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="336.43253"
         x="348.20612"
         id="tspan4672"
         sodipodi:role="line">1</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="348.20612"
       y="388.42078"
       id="text4780"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4782"
         x="348.20612"
         y="388.42078">2</tspan></text>
  </g>
</svg>
</center></td>
	</tr>

	<tr><td colspan=2><center><p><u>After x = 0</u></p></center></td></tr>
	<tr>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="189"
   height="112"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_ref2.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="3.1930291"
     inkscape:cx="102.3968"
     inkscape:cy="55.924125"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-229.8895,-314.94547)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="336.43253"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="328.20612"
         y="336.43253">x:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4442"
       width="51.160553"
       height="28.891205"
       x="320.36185"
       y="317.48697"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="387.52158"
       id="text4450"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4452"
         x="328.20612"
         y="387.52158">a:</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4454"
       width="50.160553"
       height="28.891205"
       x="320.36185"
       y="367.69156"
       rx="0"
       ry="0" />
    <text
       sodipodi:linespacing="125%"
       id="text4460"
       y="389.0737"
       x="297.93231"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:14px"
         id="tspan4464"
         y="389.0737"
         x="297.93231"
         sodipodi:role="line">f 's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4468"
       d="M 307.86505,370.87686 C 307.86505,370.87686 305.76639,369.09185 303.98138,370.87686 301.18984,374.11036 307.76644,383.23676 301.94245,384.08135"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,397.28801 C 307.86505,397.28801 305.76639,399.07302 303.98138,397.28801 301.18984,394.05451 307.76644,384.92811 301.94245,384.08352"
       id="path4470"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="326.8483"
       id="text4476"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="326.8483"
         id="tspan4478"
         style="font-size:14px">Static Data</tspan><tspan
         sodipodi:role="line"
         x="297.93231"
         y="344.3483"
         style="font-size:14px"
         id="tspan4480">Area</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,317.4903 C 307.86505,317.4903 305.76639,315.70529 303.98138,317.4903 301.18984,320.7238 307.76644,329.8502 301.94245,330.69479"
       id="path4482"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4484"
       d="M 307.86505,343.90145 C 307.86505,343.90145 305.76639,345.68646 303.98138,343.90145 301.18984,340.66795 307.76644,331.54155 301.94245,330.69696"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Lend)"
       d="M 353.414,383.9075 C 421.14499,396.00608 429.78306,365.13182 408.914,339.9075 398.85366,328.9787 372.414,330.9075 372.414,330.9075"
       id="path4486"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <text
       sodipodi:linespacing="125%"
       id="text4670"
       y="336.43253"
       x="348.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="336.43253"
         x="348.20612"
         id="tspan4672"
         sodipodi:role="line">0</tspan></text>
  </g>
</svg>
</center></td>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="189"
   height="112"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_val2.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="3.1930291"
     inkscape:cx="102.3968"
     inkscape:cy="55.924125"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-229.8895,-314.94547)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="336.43253"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="328.20612"
         y="336.43253">x:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4442"
       width="51.160553"
       height="28.891205"
       x="320.36185"
       y="317.48697"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="387.52158"
       id="text4450"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4452"
         x="328.20612"
         y="387.52158">a:</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4454"
       width="50.160553"
       height="28.891205"
       x="320.36185"
       y="367.69156"
       rx="0"
       ry="0" />
    <text
       sodipodi:linespacing="125%"
       id="text4460"
       y="389.0737"
       x="297.93231"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:14px"
         id="tspan4464"
         y="389.0737"
         x="297.93231"
         sodipodi:role="line">f 's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4468"
       d="M 307.86505,370.87686 C 307.86505,370.87686 305.76639,369.09185 303.98138,370.87686 301.18984,374.11036 307.76644,383.23676 301.94245,384.08135"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,397.28801 C 307.86505,397.28801 305.76639,399.07302 303.98138,397.28801 301.18984,394.05451 307.76644,384.92811 301.94245,384.08352"
       id="path4470"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="326.8483"
       id="text4476"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="326.8483"
         id="tspan4478"
         style="font-size:14px">Static Data</tspan><tspan
         sodipodi:role="line"
         x="297.93231"
         y="344.3483"
         style="font-size:14px"
         id="tspan4480">Area</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,317.4903 C 307.86505,317.4903 305.76639,315.70529 303.98138,317.4903 301.18984,320.7238 307.76644,329.8502 301.94245,330.69479"
       id="path4482"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4484"
       d="M 307.86505,343.90145 C 307.86505,343.90145 305.76639,345.68646 303.98138,343.90145 301.18984,340.66795 307.76644,331.54155 301.94245,330.69696"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       sodipodi:linespacing="125%"
       id="text4670"
       y="336.43253"
       x="348.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="336.43253"
         x="348.20612"
         id="tspan4672"
         sodipodi:role="line">0</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="348.20612"
       y="388.42078"
       id="text4780"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4782"
         x="348.20612"
         y="388.42078">2</tspan></text>
  </g>
</svg>
</center></td>
	</tr>

	<tr><td colspan=2><center>
		<p><u>After call</u></p>
		<p>When f returns the final value of value-result parameter <tt>a</tt>
			is copied back into the space for <tt>x</tt>, so:
		</p>
		</center></td></tr>
	<tr>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="189"
   height="112"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_ref3.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="3.1930291"
     inkscape:cx="102.3968"
     inkscape:cy="74.519319"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-229.8895,-314.94547)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="336.43253"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="328.20612"
         y="336.43253">x:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4442"
       width="51.160553"
       height="28.891205"
       x="320.36185"
       y="317.48697"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="326.8483"
       id="text4476"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="326.8483"
         id="tspan4478"
         style="font-size:14px">Static Data</tspan><tspan
         sodipodi:role="line"
         x="297.93231"
         y="344.3483"
         style="font-size:14px"
         id="tspan4480">Area</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,317.4903 C 307.86505,317.4903 305.76639,315.70529 303.98138,317.4903 301.18984,320.7238 307.76644,329.8502 301.94245,330.69479"
       id="path4482"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4484"
       d="M 307.86505,343.90145 C 307.86505,343.90145 305.76639,345.68646 303.98138,343.90145 301.18984,340.66795 307.76644,331.54155 301.94245,330.69696"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       sodipodi:linespacing="125%"
       id="text4670"
       y="336.43253"
       x="348.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="336.43253"
         x="348.20612"
         id="tspan4672"
         sodipodi:role="line">0</tspan></text>
  </g>
</svg>
</center></td>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="189"
   height="112"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_val3.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="3.1930291"
     inkscape:cx="102.3968"
     inkscape:cy="55.924125"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-229.8895,-314.94547)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="336.43253"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="328.20612"
         y="336.43253">x:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4442"
       width="51.160553"
       height="28.891205"
       x="320.36185"
       y="317.48697"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="326.8483"
       id="text4476"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="326.8483"
         id="tspan4478"
         style="font-size:14px">Static Data</tspan><tspan
         sodipodi:role="line"
         x="297.93231"
         y="344.3483"
         style="font-size:14px"
         id="tspan4480">Area</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,317.4903 C 307.86505,317.4903 305.76639,315.70529 303.98138,317.4903 301.18984,320.7238 307.76644,329.8502 301.94245,330.69479"
       id="path4482"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4484"
       d="M 307.86505,343.90145 C 307.86505,343.90145 305.76639,345.68646 303.98138,343.90145 301.18984,340.66795 307.76644,331.54155 301.94245,330.69696"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       sodipodi:linespacing="125%"
       id="text4670"
       y="336.43253"
       x="348.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="336.43253"
         x="348.20612"
         id="tspan4672"
         sodipodi:role="line">2</tspan></text>
  </g>
</svg>
</center></td>
	</tr>

	<tr><td colspan=2><center>
		<p><u>Output</u></p>
	</td></tr>
	<tr>
		<td><center><p>0</p></center></td>
		<td><center><p>2</p></center></td>
	</tr>
</table>


<a name="alias3"></a>
<P>
<H3>Creating Aliases by Passing Same Argument Twice</H3>
<P>
Consider the following C++ code:
<UL>
<pre>
  void f(int &a, &b)
  { a = 2;
    b = 4;
  }
   
  main()
  { int x;
    f(x, x);
    cout << x;
  } 
</pre>
</UL>
Assume that <tt>f</tt>'s parameters are passed by reference.
In this case,
when <tt>main</tt> calls <tt>f</tt>, <tt>a</tt> and <tt>b</tt> are aliases.
As in the previous example, different output may be produced in this
case than would be produced if <tt>f</tt>'s parameters were passed by
value-result (in which case, no aliases would be created by the
call to <tt>f</tt>, but there would be a question as to the order in
which values were copied back after the call).
Here are pictures illustrating the difference:
<table>
	<tr>
		<td width=50%><center><p><b>Call-by-reference</b></p></center></td>
		<td width=50%><center><p><b>Call-by-value-result</b></p></center></td>
	</tr>

	<tr><td colspan=2><center><p><u>At time of call</u></p></center></td></tr>
	<tr>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="160"
   height="95"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_ref2_2.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         id="path3816"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="2.2578126"
     inkscape:cx="60.442199"
     inkscape:cy="42.579551"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-237.70387,-334.05905)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="357.72891"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="328.20612"
         y="357.72891">b:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4442"
       width="50.274738"
       height="28.891205"
       x="320.36185"
       y="338.78336"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="387.52158"
       id="text4450"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4452"
         x="328.20612"
         y="387.52158">a:</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4454"
       width="50.160553"
       height="28.891205"
       x="320.36185"
       y="367.69156"
       rx="0"
       ry="0" />
    <text
       sodipodi:linespacing="125%"
       id="text4460"
       y="372.02179"
       x="297.93231"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:14px"
         id="tspan4464"
         y="372.02179"
         x="297.93231"
         sodipodi:role="line">f 's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4468"
       d="M 308.07845,341.49462 C 308.07845,341.49462 305.82856,338.01632 303.91491,341.49462 300.9222,347.79547 307.97274,365.57932 301.72905,367.2251"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       sodipodi:linespacing="125%"
       id="text5057"
       y="415.39481"
       x="328.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="415.39481"
         x="328.20612"
         id="tspan5059"
         sodipodi:role="line">x:</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 308.16892,393.23255 C 308.16892,393.23255 305.85491,396.80997 303.88673,393.23255 300.80874,386.75215 308.0602,368.46151 301.63858,366.76884"
       id="path5065"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="M 121.7993,38.256057 C 161.80276,26.431438 173.8885,65.003983 161.6609,101.84775 156.95252,114.48396 141.50864,110.44983 141.50864,110.44983"
       id="path5581"
       inkscape:connector-curvature="0"
       transform="translate(229.8895,314.94547)"
       sodipodi:nodetypes="ccc" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="M 123.34948,67.930797 C 123.34948,67.930797 150.21917,73.207388 154.57439,77.674742 157.88229,82.905105 155.04762,96.046791 149.94819,104.3404 148.15064,107.26391 144.90608,107.06253 141.7301,108.23529"
       id="path5583"
       inkscape:connector-curvature="0"
       transform="translate(229.8895,314.94547)"
       sodipodi:nodetypes="ccsc" />
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="348.58557"
       y="415.86319"
       id="text5953"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5955"
         x="348.58557"
         y="415.86319">?</tspan></text>
  </g>
</svg>
</center></td>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="160"
   height="95"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_ref2_1.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         id="path3816"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="2.5488588"
     inkscape:cx="44.583153"
     inkscape:cy="23.106884"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-237.70387,-334.05905)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="357.72891"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="328.20612"
         y="357.72891">b:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4442"
       width="50.274738"
       height="28.891205"
       x="320.36185"
       y="338.78336"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="387.52158"
       id="text4450"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4452"
         x="328.20612"
         y="387.52158">a:</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4454"
       width="50.160553"
       height="28.891205"
       x="320.36185"
       y="367.69156"
       rx="0"
       ry="0" />
    <text
       sodipodi:linespacing="125%"
       id="text4460"
       y="372.02179"
       x="297.93231"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:14px"
         id="tspan4464"
         y="372.02179"
         x="297.93231"
         sodipodi:role="line">f 's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4468"
       d="M 308.07845,341.49462 C 308.07845,341.49462 305.82856,338.01632 303.91491,341.49462 300.9222,347.79547 307.97274,365.57932 301.72905,367.2251"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       sodipodi:linespacing="125%"
       id="text5057"
       y="415.39481"
       x="328.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="415.39481"
         x="328.20612"
         id="tspan5059"
         sodipodi:role="line">x:</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 308.16892,393.23255 C 308.16892,393.23255 305.85491,396.80997 303.88673,393.23255 300.80874,386.75215 308.0602,368.46151 301.63858,366.76884"
       id="path5065"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="348.58557"
       y="415.86319"
       id="text5953"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5955"
         x="348.58557"
         y="415.86319">?</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text6161"
       y="387.86319"
       x="348.58557"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="387.86319"
         x="348.58557"
         id="tspan6163"
         sodipodi:role="line">?</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="348.58557"
       y="357.86319"
       id="text6165"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan6167"
         x="348.58557"
         y="357.86319">?</tspan></text>
  </g>
</svg>
</center></td>
	</tr>

	<tr><td colspan=2><center><p><u>After a = 2</u></p></center></td></tr>
	<tr>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="160"
   height="95"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_ref1.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         id="path3816"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="2.2578126"
     inkscape:cx="60.442199"
     inkscape:cy="24.863289"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-237.70387,-334.05905)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="357.72891"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="328.20612"
         y="357.72891">b:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4442"
       width="50.274738"
       height="28.891205"
       x="320.36185"
       y="338.78336"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="387.52158"
       id="text4450"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4452"
         x="328.20612"
         y="387.52158">a:</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4454"
       width="50.160553"
       height="28.891205"
       x="320.36185"
       y="367.69156"
       rx="0"
       ry="0" />
    <text
       sodipodi:linespacing="125%"
       id="text4460"
       y="372.02179"
       x="297.93231"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:14px"
         id="tspan4464"
         y="372.02179"
         x="297.93231"
         sodipodi:role="line">f 's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4468"
       d="M 308.07845,341.49462 C 308.07845,341.49462 305.82856,338.01632 303.91491,341.49462 300.9222,347.79547 307.97274,365.57932 301.72905,367.2251"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       sodipodi:linespacing="125%"
       id="text5057"
       y="415.39481"
       x="328.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="415.39481"
         x="328.20612"
         id="tspan5059"
         sodipodi:role="line">x:</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 308.16892,393.23255 C 308.16892,393.23255 305.85491,396.80997 303.88673,393.23255 300.80874,386.75215 308.0602,368.46151 301.63858,366.76884"
       id="path5065"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="M 121.7993,38.256057 C 161.80276,26.431438 173.8885,65.003983 161.6609,101.84775 156.95252,114.48396 141.50864,110.44983 141.50864,110.44983"
       id="path5581"
       inkscape:connector-curvature="0"
       transform="translate(229.8895,314.94547)"
       sodipodi:nodetypes="ccc" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="M 123.34948,67.930797 C 123.34948,67.930797 150.21917,73.207388 154.57439,77.674742 157.88229,82.905105 155.04762,96.046791 149.94819,104.3404 148.15064,107.26391 144.90608,107.06253 141.7301,108.23529"
       id="path5583"
       inkscape:connector-curvature="0"
       transform="translate(229.8895,314.94547)"
       sodipodi:nodetypes="ccsc" />
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="348.58557"
       y="415.86319"
       id="text5953"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5955"
         x="348.58557"
         y="415.86319">2</tspan></text>
  </g>
</svg>
</center></td>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="160"
   height="95"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_val2_1.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         id="path3816"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="2.5488588"
     inkscape:cx="44.583153"
     inkscape:cy="38.800182"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-237.70387,-334.05905)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="357.72891"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="328.20612"
         y="357.72891">b:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4442"
       width="50.274738"
       height="28.891205"
       x="320.36185"
       y="338.78336"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="387.52158"
       id="text4450"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4452"
         x="328.20612"
         y="387.52158">a:</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4454"
       width="50.160553"
       height="28.891205"
       x="320.36185"
       y="367.69156"
       rx="0"
       ry="0" />
    <text
       sodipodi:linespacing="125%"
       id="text4460"
       y="372.02179"
       x="297.93231"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:14px"
         id="tspan4464"
         y="372.02179"
         x="297.93231"
         sodipodi:role="line">f 's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4468"
       d="M 308.07845,341.49462 C 308.07845,341.49462 305.82856,338.01632 303.91491,341.49462 300.9222,347.79547 307.97274,365.57932 301.72905,367.2251"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       sodipodi:linespacing="125%"
       id="text5057"
       y="415.39481"
       x="328.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="415.39481"
         x="328.20612"
         id="tspan5059"
         sodipodi:role="line">x:</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 308.16892,393.23255 C 308.16892,393.23255 305.85491,396.80997 303.88673,393.23255 300.80874,386.75215 308.0602,368.46151 301.63858,366.76884"
       id="path5065"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="348.58557"
       y="415.86319"
       id="text5953"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5955"
         x="348.58557"
         y="415.86319">?</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text6161"
       y="387.86319"
       x="348.58557"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="387.86319"
         x="348.58557"
         id="tspan6163"
         sodipodi:role="line">2</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="348.58557"
       y="357.86319"
       id="text6165"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan6167"
         x="348.58557"
         y="357.86319">?</tspan></text>
  </g>
</svg>
</center></td>
	</tr>

	<tr><td colspan=2><center><p><u>After b = 4</u></p></center></td></tr>
	<tr>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="160"
   height="95"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_ref2_1.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         id="path3816"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="2.2578126"
     inkscape:cx="60.442199"
     inkscape:cy="-10.569236"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-237.70387,-334.05905)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="357.72891"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="328.20612"
         y="357.72891">b:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4442"
       width="50.274738"
       height="28.891205"
       x="320.36185"
       y="338.78336"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="387.52158"
       id="text4450"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4452"
         x="328.20612"
         y="387.52158">a:</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4454"
       width="50.160553"
       height="28.891205"
       x="320.36185"
       y="367.69156"
       rx="0"
       ry="0" />
    <text
       sodipodi:linespacing="125%"
       id="text4460"
       y="372.02179"
       x="297.93231"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:14px"
         id="tspan4464"
         y="372.02179"
         x="297.93231"
         sodipodi:role="line">f 's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4468"
       d="M 308.07845,341.49462 C 308.07845,341.49462 305.82856,338.01632 303.91491,341.49462 300.9222,347.79547 307.97274,365.57932 301.72905,367.2251"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       sodipodi:linespacing="125%"
       id="text5057"
       y="415.39481"
       x="328.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="415.39481"
         x="328.20612"
         id="tspan5059"
         sodipodi:role="line">x:</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 308.16892,393.23255 C 308.16892,393.23255 305.85491,396.80997 303.88673,393.23255 300.80874,386.75215 308.0602,368.46151 301.63858,366.76884"
       id="path5065"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="M 121.7993,38.256057 C 161.80276,26.431438 173.8885,65.003983 161.6609,101.84775 156.95252,114.48396 141.50864,110.44983 141.50864,110.44983"
       id="path5581"
       inkscape:connector-curvature="0"
       transform="translate(229.8895,314.94547)"
       sodipodi:nodetypes="ccc" />
    <path
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1;marker-end:url(#Arrow2Mend)"
       d="M 123.34948,67.930797 C 123.34948,67.930797 150.21917,73.207388 154.57439,77.674742 157.88229,82.905105 155.04762,96.046791 149.94819,104.3404 148.15064,107.26391 144.90608,107.06253 141.7301,108.23529"
       id="path5583"
       inkscape:connector-curvature="0"
       transform="translate(229.8895,314.94547)"
       sodipodi:nodetypes="ccsc" />
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="348.58557"
       y="415.86319"
       id="text5953"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5955"
         x="348.58557"
         y="415.86319">4</tspan></text>
  </g>
</svg>
</center></td>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="160"
   height="95"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_val2_2.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         id="path3816"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="2.5488588"
     inkscape:cx="44.583153"
     inkscape:cy="38.800182"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-237.70387,-334.05905)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="357.72891"
       id="text2987"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="328.20612"
         y="357.72891">b:</tspan></text>
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4442"
       width="50.274738"
       height="28.891205"
       x="320.36185"
       y="338.78336"
       rx="0"
       ry="0" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="328.20612"
       y="387.52158"
       id="text4450"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan4452"
         x="328.20612"
         y="387.52158">a:</tspan></text>
    <rect
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect4454"
       width="50.160553"
       height="28.891205"
       x="320.36185"
       y="367.69156"
       rx="0"
       ry="0" />
    <text
       sodipodi:linespacing="125%"
       id="text4460"
       y="372.02179"
       x="297.93231"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         style="font-size:14px"
         id="tspan4464"
         y="372.02179"
         x="297.93231"
         sodipodi:role="line">f 's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4468"
       d="M 308.07845,341.49462 C 308.07845,341.49462 305.82856,338.01632 303.91491,341.49462 300.9222,347.79547 307.97274,365.57932 301.72905,367.2251"
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       sodipodi:linespacing="125%"
       id="text5057"
       y="415.39481"
       x="328.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="415.39481"
         x="328.20612"
         id="tspan5059"
         sodipodi:role="line">x:</tspan></text>
    <path
       style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 308.16892,393.23255 C 308.16892,393.23255 305.85491,396.80997 303.88673,393.23255 300.80874,386.75215 308.0602,368.46151 301.63858,366.76884"
       id="path5065"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="348.58557"
       y="415.86319"
       id="text5953"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5955"
         x="348.58557"
         y="415.86319">?</tspan></text>
    <text
       sodipodi:linespacing="125%"
       id="text6161"
       y="387.86319"
       x="348.58557"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="387.86319"
         x="348.58557"
         id="tspan6163"
         sodipodi:role="line">2</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="348.58557"
       y="357.86319"
       id="text6165"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan6167"
         x="348.58557"
         y="357.86319">4</tspan></text>
  </g>
</svg>
</center></td>
	</tr>

	<tr><td colspan=2><center><p><u>After call</u></p></center></td></tr>
	<tr>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="160"
   height="95"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_ref2_3.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         id="path3816"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="2.2578126"
     inkscape:cx="64.862609"
     inkscape:cy="-10.569236"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-237.70387,-334.05905)">
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       sodipodi:linespacing="125%"
       id="text5057"
       y="415.39481"
       x="328.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="415.39481"
         x="328.20612"
         id="tspan5059"
         sodipodi:role="line">x:</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="348.58557"
       y="415.86319"
       id="text5953"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5955"
         x="348.58557"
         y="415.86319">4</tspan></text>
    <flowRoot
       xml:space="preserve"
       id="flowRoot6014"
       style="fill:black;stroke:none;stroke-opacity:1;stroke-width:1px;stroke-linejoin:miter;stroke-linecap:butt;fill-opacity:1;font-family:Bitstream Charter;font-style:normal;font-weight:normal;font-size:18px;line-height:125%;letter-spacing:0px;word-spacing:0px;-inkscape-font-specification:Bitstream Charter;font-stretch:normal;font-variant:normal;text-anchor:start;text-align:start;writing-mode:lr"><flowRegion
         id="flowRegion6016"><rect
           id="rect6018"
           width="196.20761"
           height="64.66436"
           x="-16.83045"
           y="-0.66781747" /></flowRegion><flowPara
         id="flowPara6020"></flowPara></flowRoot>  </g>
</svg>
</center></td>
		<td><center><svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="160"
   height="95"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_val2_3.svg">
  <defs
     id="defs4">
    <marker
       inkscape:stockid="Arrow2Mend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Mend"
       style="overflow:visible">
      <path
         id="path3816"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="scale(-0.6,-0.6)"
         inkscape:connector-curvature="0" />
    </marker>
    <marker
       inkscape:stockid="Arrow2Lend"
       orient="auto"
       refY="0"
       refX="0"
       id="Arrow2Lend"
       style="overflow:visible">
      <path
         id="path3810"
         style="fill-rule:evenodd;stroke-width:0.625;stroke-linejoin:round"
         d="M 8.7185878,4.0337352 -2.2072895,0.01601326 8.7185884,-4.0017078 C 6.97309,-1.6296469 6.9831476,1.6157441 8.7185878,4.0337352 z"
         transform="matrix(-1.1,0,0,-1.1,-1.1,0)"
         inkscape:connector-curvature="0" />
    </marker>
  </defs>
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="2.5488588"
     inkscape:cx="44.583153"
     inkscape:cy="38.800182"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-237.70387,-334.05905)">
    <rect
       ry="0"
       rx="0"
       y="397.39005"
       x="320.36185"
       height="28.891205"
       width="50.160553"
       id="rect4430"
       style="fill:none;stroke:#000000;stroke-width:0.89999998;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <path
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"
       d="M 307.86505,399.45224 C 307.86505,399.45224 305.76639,397.66723 303.98138,399.45224 301.18984,402.68574 307.76644,411.81214 301.94245,412.65673"
       id="path4438"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="ccc" />
    <text
       xml:space="preserve"
       style="font-size:18px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:end;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:end;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="297.93231"
       y="417.35797"
       id="text4444"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         x="297.93231"
         y="417.35797"
         id="tspan4448"
         style="font-size:14px">main's AR</tspan></text>
    <path
       sodipodi:nodetypes="ccc"
       inkscape:connector-curvature="0"
       id="path4466"
       d="M 307.86505,425.86339 C 307.86505,425.86339 305.76639,427.6484 303.98138,425.86339 301.18984,422.62989 307.76644,413.50349 301.94245,412.6589"
       style="fill:none;stroke:#000000;stroke-width:0.82012343;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none" />
    <text
       sodipodi:linespacing="125%"
       id="text5057"
       y="415.39481"
       x="328.20612"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       xml:space="preserve"><tspan
         y="415.39481"
         x="328.20612"
         id="tspan5059"
         sodipodi:role="line">x:</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Bitstream Charter;-inkscape-font-specification:Bitstream Charter"
       x="348.58557"
       y="415.86319"
       id="text5953"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan5955"
         x="348.58557"
         y="415.86319">?</tspan></text>
  </g>
</svg>
</center></td>
	</tr>

	<tr><td colspan=2><center><p><u>Output</u></p></center></td></tr>
	<tr>
		<td><center>4</center></td>
		<td><center>???</center></td>
	</tr>
</table>
<p>
With value-result parameter passing, the value of <tt>x</tt> after the call is
undefined, since it is unknown whether <tt>a</tt> or <tt>b</tt> gets copied
back into <tt>x</tt> first.  This may be handled in several ways:
</p>
<UL>
  <LI> Code like this (where multiple actual parameters that are passed by
       value have the same l-value) may cause a compile-time error.
  <LI> The order in which parameter values are copied back after a call
       may be defined by the specific language.
  <LI> The order in which parameter values are copied back after a call
       may be left as implementation dependent (so code like the above may
       produce different outputs when compiled with different compilers).
</UL>
<P>
<hr>
<a name=youtry4>
<P>
<center>
<u><b>TEST YOURSELF #4</b></u>
</center></a>
<P>
Assume that all parameters are passed by value-result.
<p>
<b>Question 1</b>:
Give a high-level description of what the code generator must do
for a method call.
<p>
<b>Question 2</b>:
Give the specific code that would be generated for the call shown below,
assuming that variables x and y are stored at offsets -8 and -12 in the
calling method's AR.
<UL>
<pre>
int x, y;
f(x, y);
</pre>
</UL>
 <p><a href="SOLUTIONS/PARAMETERS-ANSWERS.html#ans4">solution</a></p>
 <hr>

<P>
<a name=name></a>
<span class=subhead>Name Parameters</span>
<P>
Call-by-name parameter passing was used in Algol.
The way to understand it (not the way it is actually implemented) is
as follows:
<UL>
  <LI> Every call statement is replaced by the body
       of the called method.
  <LI> Each occurrence of a parameter in the called method is replaced
       with the corresponding argument -- the actual text of the argument,
       not its value.
</UL>
For example, given this code:
<div class=row>
	<div class=12u>
		<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="265"
   height="240"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="New document 1">
  <defs
     id="defs4" />
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="1.4142136"
     inkscape:cx="176.66767"
     inkscape:cy="156.42518"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-106.85741,-128.71141)">
    <text
       xml:space="preserve"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
       x="108.85741"
       y="137.65965"
       id="text2985"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan2987"
         x="108.85741"
         y="137.65965">void Init(int x, int y)</tspan><tspan
         sodipodi:role="line"
         x="108.85741"
         y="155.15965"
         id="tspan2989">{ for (int k = 0; i &lt; 10; k++){</tspan><tspan
         sodipodi:role="line"
         x="108.85741"
         y="172.65965"
         id="tspan2993">    y = 0;</tspan><tspan
         sodipodi:role="line"
         x="108.85741"
         y="190.15965"
         id="tspan2997">    x++;  </tspan><tspan
         sodipodi:role="line"
         x="108.85741"
         y="207.65965"
         id="tspan2995">  } </tspan><tspan
         sodipodi:role="line"
         x="108.85741"
         y="225.15965"
         id="tspan2991">}</tspan><tspan
         sodipodi:role="line"
         x="108.85741"
         y="242.65965"
         id="tspan2999" /><tspan
         sodipodi:role="line"
         x="108.85741"
         y="260.15967"
         id="tspan3001">main()</tspan><tspan
         sodipodi:role="line"
         x="108.85741"
         y="277.65967"
         id="tspan3003">{ int j;</tspan><tspan
         sodipodi:role="line"
         x="108.85741"
         y="295.15967"
         id="tspan3007">  int A[10];</tspan><tspan
         sodipodi:role="line"
         x="108.85741"
         y="312.65967"
         id="tspan3009" /><tspan
         sodipodi:role="line"
         x="108.85741"
         y="330.15967"
         id="tspan3011">  j = 0;</tspan><tspan
         sodipodi:role="line"
         x="108.85741"
         y="347.65967"
         id="tspan3013">  Init(j, A[j]);</tspan><tspan
         sodipodi:role="line"
         x="108.85741"
         y="365.15967"
         id="tspan3005">}</tspan></text>
  </g>
</svg>
	</div>
</div>

The following shows this (conceptual) substitution, with the substituted
code in the dashed box:
<div class=row>
	<div class=12u>
		<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="375"
   height="204"
   id="svg2"
   version="1.1"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_name_call.svg">
  <defs
     id="defs4" />
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="2.2578125"
     inkscape:cx="234.85149"
     inkscape:cy="161.15819"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     showgrid="false"
     inkscape:window-width="700"
     inkscape:window-height="724"
     inkscape:window-x="665"
     inkscape:window-y="0"
     inkscape:window-maximized="0"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title />
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="layer1"
     transform="translate(-377.27007,-127.76312)">
    <text
       sodipodi:linespacing="125%"
       id="text3015"
       y="137.65965"
       x="481.85742"
       style="font-size:14px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
       xml:space="preserve"><tspan
         id="tspan3033"
         y="137.65965"
         x="481.85742"
         sodipodi:role="line">main(){</tspan><tspan
         y="155.15965"
         x="481.85742"
         sodipodi:role="line"
         id="tspan3053">  int j;</tspan><tspan
         id="tspan3035"
         y="172.65965"
         x="481.85742"
         sodipodi:role="line">  int A[10];</tspan><tspan
         id="tspan3037"
         y="190.15965"
         x="481.85742"
         sodipodi:role="line" /><tspan
         id="tspan3039"
         y="207.65965"
         x="481.85742"
         sodipodi:role="line">  j = 0;</tspan><tspan
         y="225.15965"
         x="481.85742"
         sodipodi:role="line"
         id="tspan3055">  for (int k = 0; i &lt; 10; k++){</tspan><tspan
         y="242.65965"
         x="481.85742"
         sodipodi:role="line"
         id="tspan3057">    A[j] = 0;</tspan><tspan
         y="260.15967"
         x="481.85742"
         sodipodi:role="line"
         id="tspan3059">    j++;  </tspan><tspan
         id="tspan3041"
         y="277.65967"
         x="481.85742"
         sodipodi:role="line">  }</tspan><tspan
         id="tspan3043"
         y="295.15967"
         x="481.85742"
         sodipodi:role="line">}</tspan></text>
    <rect
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0"
       id="rect3093"
       width="42.5"
       height="14.787689"
       x="510"
       y="231.61218"
       ry="7.3938446"
       rx="9.25" />
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 449.5,226.36218 C 466.30093,254.29604 490.1181,229.53033 509.5,238.61218"
       id="path3095"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <text
       xml:space="preserve"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:middle;fill:#800080;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa"
       x="430.08688"
       y="201.52029"
       id="text3099"
       sodipodi:linespacing="125%"><tspan
         sodipodi:role="line"
         id="tspan3101"
         x="433.96579"
         y="201.52029">Actual A[j] for </tspan><tspan
         sodipodi:role="line"
         x="430.08688"
         y="216.52029"
         id="tspan3103">formal y</tspan></text>
    <rect
       rx="5.6936755"
       ry="4.1234655"
       y="249.99695"
       x="512.12134"
       height="14.257324"
       width="11.387351"
       id="rect3105"
       style="fill:none;stroke:#800080;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;stroke-dashoffset:0" />
    <text
       sodipodi:linespacing="125%"
       id="text3107"
       y="311.77512"
       x="613.55371"
       style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:middle;fill:#800080;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa"
       xml:space="preserve"><tspan
         y="311.77512"
         x="617.43262"
         id="tspan3109"
         sodipodi:role="line">Actual j for </tspan><tspan
         id="tspan3111"
         y="326.77512"
         x="613.55371"
         sodipodi:role="line">formal y</tspan></text>
    <path
       style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
       d="M 569.67262,311.92463 C 549.20086,337.03732 527.60824,268.65229 519.92462,264.57969"
       id="path3113"
       inkscape:connector-curvature="0"
       sodipodi:nodetypes="cc" />
    <rect
       style="fill:none;stroke:#666666;stroke-width:1;stroke-linecap:butt;stroke-linejoin:bevel;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:3, 1;stroke-dashoffset:0"
       id="rect3115"
       width="257"
       height="73"
       x="491"
       y="211.36218"
       rx="0"
       ry="0" />
  </g>
</svg>
	</div>
</div>
<p>
Call-by-name parameter passing is not really implemented like macro
expansion however; it is implemented as follows.
Instead of passing values or addresses as arguments, a method (actually
the address of a method) is passed for each argument.
These methods are called 'thunks'.
Each 'thunk' knows how to determine the address of the corresponding
argument.
So for the above example:
<UL>
  <LI> 'thunk' for j - return address of j
  <LI> 'thunk' for A[j] - return the address of the jth element of A,
       using the <em><b>current</b></em> value of j
</UL>
Each time a parameter is used, the 'thunk' is called; then the address
returned by the 'thunk' is used.
<P>
For the example above, call-by-reference would execute <tt>A[0] = 0</tt>
ten times, while call-by-name initializes the whole array!
<P>
The effect of evaluating argument expressions in the callee as needed can have 
some surprising effects. For example,  an argument that would otherwise cause a 
runtime crash (say divide-by-zero)
won't cause any problems until it is actually used (if at all). Factors like 
these
often make call-by-name programs hard to understand - it may require
looking at every call of a method to figure out what that method is doing. 
</p>
<p>
Call-by-name is interesting for historical, research, and academic reasons, 
However, it is considered too confusing for developers in practice and industry 
has largely passed it by in favor of call-by-value or call-by-reference. 
</p>
<P>
<a name=summ></a>
<span class=subhead>Comparisons of These Parameter Passing Mechanisms</span>
<P>
Here are some advantages of each of the parameter-passing mechanisms
discussed above:
<P>
<b>Call-by-Value</b> (when not used to pass pointers)
<UL>
  <LI> Doesn't cause aliasing.
  <LI> Arguments unchanged by method call, so easier to understand
       calling code (no need to go look at called method to see what it
       does to actual parameters).
  <LI> Easier for static analysis (for both programmer and compiler).
       For example:
       <pre>
        x = 0;
        f(x);           {call-by-value so x not changed}
        z = x + 1;      {can replace by z = 1 when optimizing}
	</pre>
  <LI> Compared with call-by-reference, the code in the called method
       is faster because there is no need for indirection to access
       formals.
</UL>
<P>
<b>Call-by-Reference</b>
<UL>
  <LI> More efficient when passing large objects (only need to copy addresses,
       not the objects themselves).
  <LI> Permits actuals to be modified (e.g., can implement <tt>swap</tt>
       method for integers).
</UL>
<P>
<b>Call-by-Value-Result</b>
<UL>
  <LI> As for call-by-value, more efficient than call-by-reference for
       small objects (because there is no overhead of pointer dereferencing
       for each use).
  <LI> If there is no aliasing, can implement call-by-value-result using
       call-by-reference for large objects, so it is still efficient.
</UL>
<P>
<b>Call-by-Name</b>
<P>
<UL>
  <LI>More efficient than other approaches when passing parameters that
      are never used.  For example:
			<div class=row>
				<div class=12u>
					<center>
					<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   version="1.1"
   width="255"
   height="185"
   id="svg2"
   inkscape:version="0.48.3.1 r9886"
   sodipodi:docname="params_ackermann.svg">
  <sodipodi:namedview
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1"
     objecttolerance="10"
     gridtolerance="10"
     guidetolerance="10"
     inkscape:pageopacity="0"
     inkscape:pageshadow="2"
     inkscape:window-width="920"
     inkscape:window-height="724"
     id="namedview22"
     showgrid="false"
     inkscape:zoom="2.8802365"
     inkscape:cx="127.5"
     inkscape:cy="92.5"
     inkscape:window-x="315"
     inkscape:window-y="0"
     inkscape:window-maximized="0"
     inkscape:current-layer="svg2" />
  <defs
     id="defs4" />
  <metadata
     id="metadata7">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title />
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <text
     sodipodi:linespacing="125%"
     style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:start;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:start;fill:#000000;fill-opacity:1;stroke:none;font-family:Cutive Mono;-inkscape-font-specification:Cutive Mono"
     xml:space="preserve"
     id="text2985"
     y="61.669914"
     x="1.9999955"><tspan
       id="tspan2987"
       y="61.669914"
       x="1.9999955">f(Ackermann(4,2),0);</tspan><tspan
       id="tspan2989"
       y="76.669914"
       x="1.9999955" /><tspan
       id="tspan2991"
       y="91.669914"
       x="1.9999955">void f(int a, int b){</tspan><tspan
       id="tspan2993"
       y="106.66991"
       x="1.9999955">  if (b == 1){</tspan><tspan
       id="tspan2997"
       y="121.66991"
       x="1.9999955">    return a + 1;</tspan><tspan
       id="tspan3001"
       y="136.66995"
       x="1.9999955">  } else {</tspan><tspan
       id="tspan3003"
       y="151.66995"
       x="1.9999955">    return 0;</tspan><tspan
       id="tspan2999"
       y="166.66995"
       x="1.9999955">  }</tspan><tspan
       id="tspan2995"
       y="181.66995"
       x="1.9999955">}</tspan></text>
  <path
     inkscape:connector-curvature="0"
     style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
     id="path3005"
     d="M 59.0274,64.44049 C 76.13962,83.51724 168.75008,76.932057 169.76504,51.736838"
     sodipodi:nodetypes="cc" />
  <path
     inkscape:connector-curvature="0"
     style="fill:none;stroke:#800080;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1"
     id="path3007"
     d="M 57.873,67.44193 58.79653,63.97873 61.79797,63.97873" />
  <text
     sodipodi:linespacing="125%"
     style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;text-align:center;line-height:125%;letter-spacing:0px;word-spacing:0px;writing-mode:lr-tb;text-anchor:middle;fill:#800080;fill-opacity:1;stroke:none;font-family:Purisa;-inkscape-font-specification:Purisa"
     xml:space="preserve"
     id="text3009"
     y="14.108606"
     x="172.68417"><tspan
       id="tspan3011"
       y="14.108606"
       x="176.56308">The Ackermann function </tspan><tspan
       id="tspan3013"
       y="29.108606"
       x="172.68417">takes enormous time to</tspan><tspan
       id="tspan3015"
       y="44.108604"
       x="172.68417">compute</tspan></text>
</svg>
					</center>
				</div>
			</div>
      If the condition <tt>b</tt> in method <tt>f</tt> is not 1, then using
      call-by-name, it is never necessary to evaluate the first actual
      at all!  That's good because doing so would take a <em><b>long</b></em>
			time<sup><a href="#" class=tooltip>*
<span><img class="callout" src="css/callout.gif"/>The Ackermann function is famously slow to evaluate. In this example, the result is an integer of nearly 20,000 digits.</span></a> 
</sup>.
</UL>

