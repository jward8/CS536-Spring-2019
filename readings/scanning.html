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
  <LI><A HREF="#fsm">Finite-State Machines</A>
  <UL>
    <LI><A HREF="#id">Example: Pascal Identifiers</A>
    <LI><A HREF="#youtry1">Test Yourself #1</A>
    <LI><A HREF="#int">Example: Integer Literals</A>
    <LI><A HREF="#formalDef">Formal Definition</A>
    <LI><A HREF="#nondet">Deterministic and Non-Deterministic FSMs</A>
    <LI><A HREF="#imp">How to Implement a FSM</A>
  </UL>
  <LI><A HREF="#regExp">Regular Expressions</A>
  <UL>
    <LI><A HREF="#youtry2">Test Yourself #2</A>
    <LI><A HREF="#reInt">Example: Integer Literals</A>
    <LI><A HREF="#set">The Language Defined by a Regular Expression</A>
  </UL>
  <LI><A HREF="#scanner">Using Regular Expressions and Finite-State Machines to Define a Scanner</A>
  <UL>
    <LI><A HREF="#youtry3">Test Yourself #3</A>
  </UL>
</UL>

<P>
<a name=overview></a>
<H2>Overview</H2>
<P>
Recall that the job of the scanner is to translate the sequence of
characters that is the input to the compiler to a corresponding sequence
of <b>tokens</b>.
In particular, each time the scanner is called it should find the
<em>longest</em> sequence of characters in the input, starting with
the current character, that corresponds to a token, and should return
that token.
<P>
It is possible to write a scanner from scratch, but a more efficient and
less error-prone approach is to use a <b>scanner generator</b> like
lex or flex (which produce C code), or jlex (which produces java code).
The input to a scanner generator includes one <b>regular expression</b>
for each token (and for each construct that must be recognized and ignored,
such as whitespace and comments).
Therefore, to use a scanner generator you need to understand regular
expressions.
To understand how the scanner generator produces code that correctly
recognizes the strings defined by the regular expressions, you need to
understand <b>finite-state machines (FSMs)</b>.
<P>
<a name=fsm></a>
<H2>Finite-State Machines</H2>
<P>
A finite-state machine is similar to a compiler in that:
</p>
<UL class="style1" style="list-style-type:disc">
  <LI> A compiler recognizes legal <em>programs</em> in some (source) language.
  <LI> A finite-state machine recognizes legal <em>strings</em> in some language.
</UL>
In both cases, the input (the program or the string) is a sequence of characters.

<P>
<a name=id></a>
<H3>Example: Pascal Identifiers</H3>
<P>
Here's an example of a finite-state-machine that recognizes Pascal identifiers
(sequences of one or more letters or digits, starting with a letter):
</p>
<img src="diagrams/fsm1.png"></img>
<p>
In this picture:
<UL class="style1" style="list-style-type:disc">
  <LI> Nodes are <em>states</em>.
  <LI> Edges (arrows) are <em>transitions</em>.
       Each edge should be labeled with a single character.  In this
       example, we've used a single edge labeled "letter" to stand for
       52 edges labeled 'a', 'b', ..., 'z', 'A', ..., 'Z'.
       (Similarly, the label "letter,digit"
       stands for 62 edges labeled 'a',...'Z','0',...'9'.)
  <LI> S is the <em>start state</em>; every FSM has exactly one (a standard
       convention is to label the start state "S").
  <LI> A is a <em>final state</em>.
       By convention, final states are drawn using a double circle,
       and non-final states are drawn using single circles.
       A FSM may have more than one final state.
</UL>
A FSM is applied to an input (a sequence of characters).
It either accepts or rejects that input.
Here's how the FSM works:
<UL class="style1" style="list-style-type:disc">
  <LI> The FSM starts in its start state.
  <LI> If there is a edge out of the current state whose label matches
       the current input character, then the FSM moves to the state
       pointed to by that edge, and "consumes" that character;
       otherwise, it gets stuck.
  <LI> The finite-state machine stops when it gets stuck or when it has
       consumed all of the input characters.
</UL>
<P>
An input string is <em>accepted</em> by a FSM if:
<UL class="style1" style="list-style-type:disc">
  <LI> The entire string is consumed (the machine did not get stuck), and
  <LI> the machine ends in a final state.
</UL>
The <em>language defined by a FSM</em> is the set of strings accepted
by the FSM.
<P>
The following strings <em>are</em> in the language of the FSM shown above:
<ul class="style1" style="list-style-type:disc">
<li><code style="font-family:Courier">x</code></li>
<li><code style="font-family:Courier">tmp2</code></li>
<li><code style="font-family:Courier">XyZzy</code></li>
<li><code style="font-family:Courier">position27</code></li>
</ul>
The following strings are <em>not</em> in the language of the FSM shown above:
<ul class="style1" style="list-style-type:disc">
<li><code style="font-family:Courier">123</code></li>
<li><code style="font-family:Courier">a?</code></li>
<li><code style="font-family:Courier">13apples</code></li>
</ul>
<P>
<hr>
<a name=youtry1>
<P>
<center>
<u><b>TEST YOURSELF #1</b></u>
</center></a>
<P>
Write a finite-state machine that accepts e-mail addresses, defined
as follows:
<UL class="style1" style="list-style-type:disc" >
  <LI> a sequence of one or more letters and/or digits,
  <LI> followed by an at-sign,
  <LI> followed by one or more letters,
  <LI> followed by zero or more extensions.
  <LI> An extension is a dot followed by one or more letters.
</UL>

<p><a href="SOLUTIONS/SCANNER-ANSWERS.html#ans1">solution</a></p>
<hr>

<P>
<a name=int></a>
<H2>Example: Integer Literals</H2>
<P>
The following is a finite-state machine that accepts integer literals
with an optional + or - sign:
</p>
<div align=center>
<img src=diagrams/fsm2.png></img>
</div>
<P>
<a name="formalDef"></a>
<H2>Formal Definition</H2>
<P>
An FSM is a 5-tuple: $(Q,\Sigma,\delta,q,F)$
<ul class="style1" style="list-style-type:disc">
  <li> $Q$ is a finite set of states ($\{\mathrm{S,A,B}\}$ in the above example).</li>
  <li> $\Sigma$ (an uppercase sigma) is the alphabet of the machine, a finite set of characters
       that label the edges ($\{+,-,0,1,...,9\}$ in the above example).
  <li> $q$ is the start state, an element of $Q$ ($\mathrm{S}$ in the above example).
  <li> $F$ is the set of final states, a subset of $Q$ ({B} in the above
       example).
  <LI> $\delta$ is the state transition relation: $Q \times \Sigma \rightarrow Q$
       (i.e., it is a function that takes two arguments -- a state in $Q$ and a
       character in $\Sigma$ -- and returns a state in $Q$).
</ul>

<p>
       Here's a definition of $\delta$ for the above example, using a <b>state
       transition table</b>:
</p>

<div align=center>
<table align=center style="width:240px; text-align:center">
<tr>
    <td align=right style="width:60px"></td>
    <td align=center><b>+</b></td>
    <td align=center><b>-</b></td>
    <td align=center>$\mathrm{digit}$</b></td>
</tr>
<tr>
    <td align=right style="width:60px">$S$</td>
    <td style="text-align:center; border:1px solid" width=60px align=center>$A$</td>
    <td style="text-align:center; border:1px solid" width=60px align=center>$A$</td>
    <td style="text-align:center; border:1px solid" width=60px align=center>$B$</td>
</tr>

<tr>
    <td align=right>$A$</td>
    <td style="text-align:center; border:1px solid" width=60px align=center></td>
    <td style="text-align:center; border:1px solid" width=60px align=center></td>
    <td style="text-align:center; border:1px solid" width=60px align=center>$B$</td>
</tr>

<tr>
    <td align=right style="width:60px">$B$</td>
    <td style="text-align:center; border:1px solid" width=60px align=center></td>
    <td style="text-align:center; border:1px solid" width=60px align=center></td>
    <td style="text-align:center; border:1px solid" width=60px align=center>$B$</td>
</tr>
</table>
</div>
<P>
<a name="nondet"></a>
<H2>Deterministic and Non-Deterministic FSMs</H2>
<P>
There are two kinds of FSM:
<OL class="style1">
	<LI> Deterministic: 
		<UL class="style1" style="list-style-type:disc">
	<li>No state has more than one outgoing edge with the same
       label.</li>
</ul>
  <LI> Non-Deterministic:
		<UL class="style1" style="list-style-type:disc">
       <LI> States <em>may</em> have more than one outgoing edge with same
            label.
       <LI> Edges may be labeled with <b>$\varepsilon$</b> (epsilon), the empty
            string.  The FSM can take an $\varepsilon$-transition <em>without</em>
            looking at the current input character.
    </UL>
</OL>
<P>
<b>Example</b>
<P>
Here is a non-deterministic finite-state machine that recognizes the
same language as the second example deterministic FSM above
(the language of integer literals with an optional sign):
</p>
<div align="center">
<img src="diagrams/fsm3.png"></img>
</div>
<P>
Sometimes, non-deterministic machines are simpler
than deterministic ones, though not in this example.
<P>
A string is accepted by a non-deterministic finite-state machine
if there exists a sequence of moves starting in
the start state, ending in a final state, that consumes the entire string.
For example, here's what happens when the above machine is run on the
input "+75":

<table style="text-align:center; border-bottom:0px solid">
<tr>
    <td align=center style="border-bottom:1px solid"><b>After scanning</b></td>
    <td align=center>&nbsp&nbsp&nbsp&nbsp&nbsp</td>
    <td colspan="2" align=center style="border-bottom:1px solid"><b>Can be in these states</b></td>
</tr>
<tr>
    <td align=center>(nothing)</td>
    <td align=center></td>
    <td align=center>$S$</td>
    <td align=center>$A$</td>
</tr>
<tr>
    <td align=center>$+$</td>
    <td align=center></td>
    <td align=center>$A$</td>
    <td align=center>(stuck)</td>
</tr>
<tr>
    <td align=center>$+7$</td>
    <td align=center></td>
    <td align=center>$B$</td>
    <td align=center>(stuck)</td>
</tr>
<tr>
    <td align=center>$+75$</td>
    <td align=center></td>
    <td align=center>$B$</td>
    <td align=center>(stuck)</td>
</tr>

</table>

There <em>is</em> one sequence of moves that consumes the entire input
and ends in a final state (state B), so this input <em>is</em> accepted
by his machine.
<P>
It is worth noting that there is a theorem that says:
<UL>
For every non-deterministic finite-state machine $M$,
there exists a <em>deterministic</em> machine $M_1$ such that $M$ and
$M_1$ accept the <em>same</em> language.
</UL>

<P>
<a name="imp"></a>
<H2>How to Implement a FSM</H2>
<P>
<P>
The most straightforward way to <em>program</em> a (deterministic)
finite-state machine is to use a <b>table-driven</b> approach.
This approach uses a table with one row for each state in the machine,
and one column for each possible character.
<span style="font-family:Courier">Table[j][k]</span> tells which state to go to from state j on character k.
(An empty entry corresponds to the machine getting stuck, which means
that the input should be rejected.)
<P>
Recall the table for the (deterministic) <a href="#int">"integer literal"</a>
FSM given above:
</p>
<div align=center>
<table align=center style="width:240px; text-align:center">
<tr>
    <td align=right style="width:60px"></td>
    <td align=center><b>+</b></td>
    <td align=center><b>-</b></td>
    <td align=center>$\mathrm{digit}$</b></td>
</tr>
<tr>
    <td align=right style="width:60px">$S$</td>
    <td style="text-align:center; border:1px solid" width=60px align=center>$A$</td>
    <td style="text-align:center; border:1px solid" width=60px align=center>$A$</td>
    <td style="text-align:center; border:1px solid" width=60px align=center>$B$</td>
</tr>

<tr>
    <td align=right>$A$</td>
    <td style="text-align:center; border:1px solid" width=60px align=center></td>
    <td style="text-align:center; border:1px solid" width=60px align=center></td>
    <td style="text-align:center; border:1px solid" width=60px align=center>$B$</td>
</tr>

<tr>
    <td align=right style="width:60px">$B$</td>
    <td style="text-align:center; border:1px solid" width=60px align=center></td>
    <td style="text-align:center; border:1px solid" width=60px align=center></td>
    <td style="text-align:center; border:1px solid" width=60px align=center>$B$</td>
</tr>
</table>
</div>
<P>
The table-driven program for a FSM works as follows:
<UL class="style1" style="list-style-type:disc">
  <LI> Have a variable named state, initialized to S (the start state).
  <LI> Repeat:
		<UL class="style1" style="list-style-type:circle">
      <LI> read the next character from the input
      <LI> use the table to assign a new value to the state variable
    </UL>
       until the machine gets stuck (the table entry is empty) or the
       entire input is read.  If the machine gets stuck, reject the input.
       Otherwise, if the current state is a <em>final</em> state,
       accept the input; otherwise, reject it.
</UL>
<P>

<P>
<a name=regExp></a>
<H2>Regular Expressions</H2>
<P>
Regular expressions provide a compact way to define a language that
can be accepted by a finite-state machine.
Regular expressions are used in the input to a scanner generator to
define each token, and to define things like whitespace and comments
that do not correspond to tokens, but must be recognized and ignored. 
<P>
As an example, recall that a Pascal identifier consists of a letter,
followed by zero or more letters or digits.
The regular expression for the language of Pascal identifiers is:
<UL>
letter . (letter | digit)*
</UL>
The following table explains the symbols used in this regular expression:
<P>
<div align=center>
<TABLE border>
<tr><td>|
    <td>means "or"
<tr><td>.
    <td> means "followed by"
<tr><td>*
    <td> means zero or more instances of
<tr><td> ( )
    <td> are used for grouping
</TABLE>
</div>
<P>
Often, the "followed by" dot is omitted, and just writing two things
next to each other means that one follows the other.
For example:
<UL>
letter (letter | digit)*
</UL>
<P>
In fact, the operands of a regular expression should be single characters
or the special character epsilon, meaning the empty string
(just as the labels on the edges of a FSM should be single characters
or epsilon).
In the above example, "letter" is used as a shorthand for:
<UL>
a | b | c | ... | z | A | ... | Z
</UL>
and similarly for "digit".
Also, we sometimes put the characters in quotes (this is necessary
if you want to use a vertical bar, a dot, or a star character).
<P>
To understand a regular expression, it is necessary to know the precedences
of the three operators.
They can be understood by analogy with the arithmetic operators
for addition, multiplication, and exponentiation:
<P>
<UL>
<TABLE border>
<tr><td> <b>Regular Expression Operator</b>
    <td> <b>Analogous Arithmetic Operator</b>
    <td> <b>Precedence</b>
<tr><td align=center> |
    <td> plus
    <td> lowest precedence
<tr><td align=center> .
    <td> times
    <td> middle
<tr><td align=center> *
    <td> exponentiation
    <td> highest precedence
</TABLE>
</UL>
<P>
So, for example, the regular expression:
<UL>
$\mathrm{letter}.\mathrm{letter} | \mathrm{digit}\mathrm{^*}$
</UL>
does <em>not</em> define the same language as the expression given above.
Since the dot operator has higher precedence than the | operator (and the
* operator has the highest precedence of all),
this expression is the same as:
<UL>
$(\mathrm{letter}.\mathrm{letter}) | (\mathrm{digit}\mathrm{^*})$
</UL>
and it means "either two letters, or zero or more digits".
<P>
<hr>
<a name=youtry2>
<P>
<center>
<u><b>TEST YOURSELF #2</b></u>
</center></a>
<P>
Describe (in English) the language defined by each of the following
regular expressions:
<OL class=style1>
  <LI> $\mathrm{digit} | \mathrm{letter} \; \mathrm{letter}$
  <LI> $\mathrm{digit} | \mathrm{letter} \; \mathrm{letter}$*
  <LI> $\mathrm{digit} | \mathrm{letter}$*
</OL>
<p><a href="SOLUTIONS/SCANNER-ANSWERS.html#ans2">solution</a></p>
<hr>

<P>
<a name=reInt></a>
<H3>Example: Integer Literals</H3>
<P>
An integer literal with an optional sign can be defined in English as:
<UL>
"(nothing or + or -) followed by one or more digits"
</UL>
The corresponding regular expression is:
<UL>
$(+|-|\varepsilon).(\mathrm{digit}.\mathrm{digit}\mathrm{^*})$
</UL>
Note that the regular expression for "one or more digits" is:
<UL>
$\mathrm{digit}.\mathrm{digit}\mathrm{^*}$
</UL>
i.e., "one digit followed by zero or more digits".
Since "one or more" is a common pattern, another operator, +, has
been defined to mean "one or more".
For example,
<UL>
$\mathrm{digit}$+
</UL>
means "one or more digits", so another way to define integer literals with
optional sign is:
<UL>
$({\textbf +}|-|\varepsilon).\mathrm{digit}$+
</UL>
<P>
<a name=set></a>
<H2>The Language Defined by a Regular Expression</H2>
<P>
Every regular expression defines a language: the set of strings that
match the expression.
We will not give a formal definition here, instead, we'll give some
examples:
<P>
<TABLE style="table-layout:auto">
<tr><td style="text-align:left; border-bottom:1px solid"><b>Regular Expression</b></td>
<td>&nbsp</td>
<td style="text-align:left; border-bottom:1px solid"><b>Corresponding Set of Strings</b></td>
<tr><td> $\varepsilon$</td>
		<td>&nbsp&nbsp&nbsp&nbsp</td>
    <td> {""}</td>
</tr>
<tr><td> a</td>
		<td>&nbsp</td>
    <td> {"a"}</td>
</tr>
<tr><td> a.b.c</td>
		<td>&nbsp</td>
    <td> {"abc"}</td>
</tr>
<tr><td> a | b | c</td>
		<td>&nbsp</td>
    <td> {"a", "b", "c"}</td>
</tr>
<tr><td> (a | b | c)*</td>
		<td>&nbsp</td>
    <td> {"", "a", "b", "c", "aa", "ab", ..., "bccabb" ...}</td>
</tr>
</TABLE>                    
<P>
<a name=scanner></a>
<H2>Using Regular Expressions and FSMs to Define a Scanner</H2>
<P>
There is a theorem that says that for every regular expression, there
is a finite-state machine that defines the same language, and vice versa.
This is relevant to scanning because it is usually easy to define the
tokens of a language using regular expressions, and then those regular
expression can be converted to finite-state machines (which can actually
be programmed).
<P>
For example, let's consider a very simple language: the language of
assignment statements in which the left-hand side is a Pascal identifier
(a letter followed by one or more letters or digits),
and the right-hand side is one of the following:
<UL>
  <LI> ID + ID
  <LI> ID * ID
  <LI> ID == ID
</UL>
This language has five tokens, which can be defined by the following
five regular expressions:
<P>
<UL>
<TABLE border>
<tr><td><b>Token</b>
    <td><b>Regular Expression</b>
<tr><td>ASSIGN
    <td>"="
<tr><td>ID
    <td>letter (letter | digit)*
<tr><td>PLUS
    <td>$+$
<tr><td>TIMES
    <td>$*$
<tr><td>EQUALS
    <td>"="."="
</TABLE>
</UL>
<P>
These regular expressions can be converted into the following finite-state
machines:
</p>

<table>
<tr>
<td valign="middle" style="padding-bottom:100px">ASSIGN:</td>
<td valign="middle" style="padding-bottom:100px">
<img style="vertical-align:middle" src="diagrams/fsm_assign.png"></img></td>
</tr>

<tr></tr>

<tr>
<td valign="middle" style="padding-bottom:100px">ID:</td>
<td valign="middle" style="padding-bottom:100px">
<img style="vertical-align:middle" src="diagrams/fsm_id.png"></img></td>
</tr>

<tr>
<td valign="middle" style="padding-bottom:100px">PLUS:</td>
<td valign="middle" style="padding-bottom:100px">
<img style="vertical-align:middle" src="diagrams/fsm_plus.png"></img></td>
</tr>

<tr>
<td valign="middle" style="padding-bottom:100px">TIMES:</td>
<td valign="middle" style="padding-bottom:100px">
<img style="vertical-align:middle" src="diagrams/fsm_times.png"></img>
</td>
</tr>

<tr>
<td valign="middle" style="padding-bottom:30px">EQUALS:</td>
<td valign="middle" style="padding-bottom:30px">
<img style="vertical-align:middle" src="diagrams/fsm_equals.png"></img></td>
</tr>
</table>

<P>
Given a FSM for each token, how do we write a scanner?
Recall that the goal of a scanner is to find the <em>longest
prefix</em> of the current input that corresponds to a token.
This has two consequences:
<OL>
  <LI> The scanner sometimes needs to look one or more characters
       <em>beyond</em>
       the last character of the current token, and then needs to "put
       back" those characters so that the next time the scanner is called
       it will have the correct current character.
       For example, when scanning a program written in the simple
       assignment-statement language defined above, if the input is
       "==", the scanner should return the EQUALS token, not two ASSIGN
       tokens.  So if the current character is "=", the scanner must
       look at the next character to see whether it is another "="
       (in which case it will return EQUALS), or is some other character
       (in which case it will put that character back and return ASSIGN).
  <P>
  <LI> It is no longer correct to run the FSM program until the
       machine gets stuck or end-of-input is reached, since in general
       the input will correspond to <em>many</em> tokens, not just
       a single token.
</OL>
<P>
Furthermore, remember that regular expressions are used both to define tokens
and to define things that must be recognized and skipped (like whitespace
and comments).
In the first case a value (the current token) must be returned when the
regular expression is matched, but in the second case the scanner should
simply start up again trying to match another regular expression.
<P>
With all this in mind, to create a scanner from a set of FSMs,
we must:
<UL class="style1" style="list-style-type:disc">
  <LI> <em>modify</em> the machines so that a state can have an
       associated <b>action</b> to "put back N characters" and/or
       to "return token XXX",
  <LI> we must <em>combine</em> the finite-state machines for all
       of the tokens in to a <em>single</em> machine, and
  <LI> we must write a program for the "combined" machine.
</UL>
<P>
For example, the FSM that recognizes Pascal identifiers must be
modified as follows:
<br />
<br />
<br />
<img src="diagrams/fsm_action_1.png"></img>
<p>
with the following table of actions:
<br />
<div class="row">
<div class=3u">
<div style="text-align:center; padding:10px; border:1px solid; border-radius:15px">
<b>Actions:</b>
<hr />
F1: put back 1 char, return ID
</div>
</div>
</div>
</p>
<P>
And here is the combined FSM for the five tokens (with the actions
noted below):
</p>
<img src="diagrams/fsm_action_2.png"></img>
<p>
with the following table of actions:
<br />
<div class="row">
<div class=3u">
<div style="text-align:center; padding:10px; border:1px solid; border-radius:15px">
<b>Actions:</b>
<div style="text-align:left">
<hr />
F1: put back 1 char; return ASSIGN
<br />
F2: put back 1 char; return ID
<br />
F3: return PLUS
<br />
F4: return TIMES
<br />
F5: return EQUALS
<br />
</div>

</div>
</div>
</div>
</p>

<P>
We can convert this FSM to code using the table-driven technique
described above, with a few small modifications:
<UL class="style1" style="list-style-type:disc">
  <LI> The table will include a column for end-of-file as well as for all
       possible characters (the end-of-file column is needed, for example,
       so that the scanner works correctly when an identifier is the last token
       in the input).
  <LI> Each table entry may include an action as well as or instead
       of a new state.
  <LI> Instead of repeating "read a character; update the state variable"
       until the machine gets stuck or the entire input is read, the code
       will repeat: "read a character; perform
       the action and/or update the state variable"
       (eventually, the action will be to return a value, so
       the scanner code will stop, and will start again in the start
       state next time it is called).
</UL>
Here's the table for the above "combined" FSM:
<P>
<table style="text-align:center">
<tr><td>
    <td align=center><b>+</b></td>
    <td align=center><b>*</b></td>
    <td align=center><b>=</b></td>
    <td align=center>$\mathrm{letter}$</td>
    <td align=center>$\mathrm{digit}$</b></td>
    <td align=center><b>EOF</b></td>
</tr>
<tr><td>$S$</td>
    <td style="text-align:center; border:1px solid">return PLUS</td>
    <td style="text-align:center; border:1px solid">return TIMES</td>
    <td style="text-align:center; border:1px solid">$B$</td>
    <td style="text-align:center; border:1px solid">$A$</td>
    <td style="text-align:center; border:1px solid"></td>
    <td style="text-align:center; border:1px solid"></td>
</tr>
<tr><td>$A$</td>
    <td style="text-align:center; border:1px solid">put back 1 char; return ID</td>
    <td style="text-align:center; border:1px solid">put back 1 char; return ID</td>
    <td style="text-align:center; border:1px solid">put back 1 char; return ID</td>
    <td style="text-align:center; border:1px solid">$A$</td>
    <td style="text-align:center; border:1px solid">$A$</td>
    <td style="text-align:center; border:1px solid">return ID</td>
</tr>
<tr><td>$B$</td>
    <td style="text-align:center; border:1px solid">put back 1 char; return ASSIGN</td>
    <td style="text-align:center; border:1px solid">put back 1 char; return ASSIGN</td>
    <td style="text-align:center; border:1px solid">return EQUALS</td>
    <td style="text-align:center; border:1px solid">put back 1 char; return ASSIGN</td>
    <td style="text-align:center; border:1px solid">put back 1 char; return ASSIGN</td>
    <td style="text-align:center; border:1px solid">return ASSIGN</td>
</tr>
</TABLE>
<hr>
<a name=youtry3>
<P>
<center>
<u><b>TEST YOURSELF #3</b></u>
</center></a>
<P>
Suppose we want to extend the very simple language of assignment
statements defined above to allow both integer and double literals
to occur on the right-hand sides of the assignments.
For example:
<UL>
x = 23 + 5.5
</UL>
would be a legal assignment.
<P>
What new tokens would have to be defined?
What are the regular expressions, the finite-state machines, and
the modified finite-state machines that define them?
How would the
the "combined" finite-state machine given above have to be augmented?
<p><a href="SOLUTIONS/SCANNER-ANSWERS.html#ans3">solution</a></p>
<hr>
</body>
</html>
